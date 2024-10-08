<script setup>
import SliderContenuti from '@/components/SliderContenuti.vue';
import Contenuto from '@/components/Contenuto.vue';
import Author from '@/components/Author.vue';
import Tags from '@/components/Tags.vue';
import Actions from '@/components/Actions.vue';
import {ref, onBeforeMount, onMounted, onUnmounted, reactive, watch, computed} from 'vue'
import { icone_tipologie_contenuto } from '@/utils/icone';
import { request } from '@/utils/request';
import { routerKey, useRoute } from 'vue-router';
import { useTerraParkStore } from '@/stores/commons';

const route = useRoute();
const tps = useTerraParkStore()

const corso = reactive({})
const stats = reactive({
    duration: false,
    pages: false,
})

const showRelated = ref(true);
const autoOpenPdf = ref(true)

const canDownloadCertificate = computed(() => {
    let countSeen = 0;
    corso?.contents.forEach(c => {
        if (c.seen) {
            countSeen++;
        }autoOpenPdf
    })

    if (countSeen == corso.contents.length) {
       return true
    } else {
        return false
    }
})

onBeforeMount(() => {
    request({
        task : "course/get",
        data : {
            permalink : route.params.permalink
        },
        callback : function(dt) {
            Object.assign(corso, dt);
            //setContentAsOpened(0)

            // Calcolo totali
            let duration = 0, pages = 0;
            corso.contents.forEach(c => {
                const meta = c.meta;
                if (meta?.duration) {
                    duration += Math.ceil(meta.duration / 60);
                }

                if (meta?.pages) {
                    pages += parseInt(meta.pages);
                }

            })

            if (duration > 0) stats.duration = duration;
            if (pages > 0) stats.pages = pages;

            // if (countSeen == corso.contents.length) {
            //     canDownloadCertificate.value = true;
            // }

            request({
                task : "userActions/getCoursePosition",
                data : {
                    course_permalink : route.params.permalink
                },
                callback : (dt) => {
                    if (dt.length > 0) {
                        corso.contents.forEach((c,i) => {
                            if (c.permalink == dt[0].permalink) {
                                if (c.media.mediaType == "pdf") {

                                    autoOpenPdf.value = false;
                                    contenutoSelezionato.value = i;

                                    setTimeout(() => autoOpenPdf.value = true, 3000)

                                } else {
                                    contenutoSelezionato.value = i;
                                }
                                
                            }
                        })
                    }
                }
            })

        }
    })
})



const sectionContenuti = ref(null)

const topCondensed = ref(false);
const toggleTop = function(condensed = null) {

    if (topCondensed.value === false) {
        topCondensed.value = true;
    } else {
        topCondensed.value = false;
        window.setTimeout(() => {
        window.scrollTo({
        top: document.querySelector(".top").offsetTop,
        behavior : 'smooth'
    })
    },50)
    window.setTimeout(() =>window.addEventListener('scroll', handleScroll), 5000)
   

    }
}

function toggleIfCondensed() {
    if (topCondensed.value) {
        toggleTop()
    }
}
// Add the scroll event listener in the onMounted hook
onMounted(() => {
  window.addEventListener('scroll', handleScroll);

});

// Remove the scroll event listener in the onUnmounted hook
onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const handleScroll = () => {
    if (window.scrollY > sectionContenuti.value?.offsetTop - 50) {
        topCondensed.value = true;
        window.removeEventListener('scroll', handleScroll);
    }
}

const contenutoSelezionato = ref(null)

const renderMeta = (meta) => {
    if (meta?.duration) {
        return `${Math.ceil(meta.duration/60)} min.`;
    }

    if (meta?.pages) {
        return `${meta.pages} pag.`;
    }

    return ``
}

watch (contenutoSelezionato, () => {
    setContentAsOpened(contenutoSelezionato.value)
})

const setContentAsOpened = (index, direct=false) => {

    if (!direct && corso.contents[index].media.mediaType == "quiz") {
        // È un quiz!
        return;
    }

    if (!corso.contents[index].seen) {
        request({
            task : "userActions/setContentAsOpened",
            data : {
                course_permalink : corso.permalink,
                permalink : corso.contents[contenutoSelezionato.value].permalink
            },
            callback : () => {
                corso.contents[contenutoSelezionato.value].seen = 1
            },
            hideLoader : true
        })
    }

    request({
        task : "userActions/updateCoursePosition",
        data : {
            course_permalink : corso.permalink,
            permalink : corso.contents[contenutoSelezionato.value].permalink
        },
        callback : () => {},
        hideLoader: true
    })
}

function getCertificate() {

    if (canDownloadCertificate.value) {
        request({
            task : `certificates/create`,
            data : {
                permalink : corso.permalink,
            },
            callback : function(dt) {
                const aElement = document.createElement('a');
                aElement.href = dt.s3.url;
                aElement.setAttribute('target', '_blank');
                aElement.click();
            }
        })
    }
   
}

let dev = import.meta.env.DEV

</script>
<template>
    <div v-if="corso.permalink" class="container large">
        <div class="row top" :class="{'minimized' : topCondensed}" @click="toggleIfCondensed">
            <div class="col-lg-6 p-5 left">
                <h1>{{ corso.title }}</h1>

                <div class="descrizione">
                    <div v-for="(par,pn) in corso.description.split('|')" :key="pn">{{ par }}</div>
                </div>

                <div class="meta mt-3">

                    <div class="topic-theme">
                        <span v-if="corso.themeID !== null ">Tema: <strong><router-link
                                    :to="`/catalogo?themes=${corso.themeID}`">{{ tps.getTheme(corso.themeID).title
                                    }}</router-link></strong></span>
                        <span v-if="corso.topicID">Argomento: <strong><router-link
                                    :to="`/catalogo?topics=${corso.topicID}`">{{ tps.getTopic(corso.topicID).title
                                    }}</router-link></strong></span>
                    </div>

                    <Author :authors="corso.authors"></Author>

                    <div class="meta-row">
                        <span v-if="stats.duration">Durata totale: <strong>{{ stats.duration }}</strong> minuti</span>
                        <span v-if="stats.pages"><strong>{{stats.pages }}</strong> pagine</span>
                    </div>

                    <Tags :permalink="route.params.permalink"></Tags>
                </div>

            </div>
            <div class="col-lg-6 p-5 right">
                <img class="image" :src="corso.image">

                <Actions :permalink="corso.permalink" />
            </div>
            <button class="toggle" @click.stop="toggleTop()">
                <span v-if="!topCondensed" class="material-symbols-outlined">keyboard_arrow_up</span>
                <span v-if="topCondensed" class="material-symbols-outlined">keyboard_arrow_down</span>
            </button>
        </div>
        <section class="my-5" ref="sectionContenuti">
            <div class="row">
                <div class="col-lg-5">
                    <h3>Contenuti del corso</h3>
                    <div class="contenuti">
                        <div v-for="(c,i) in corso.contents" :key="i" class="contenuto"
                            :class="{'selected' : i===contenutoSelezionato}" @click="() => contenutoSelezionato=i">
                            <div class="stato">
                                <span class="material-symbols-outlined">{{
                                    ['check_box_outline_blank','check_box'][c.seen ? 1 : 0] }}</span>
                            </div>
                            <div class="titolo">
                                {{ i+1 }}. {{ c.title }}
                            </div>
                            <div class="meta text-muted">
                                <span class="material-symbols-outlined">{{ tps.getTypology(c.typologyID).icon }}</span>
                                <span>{{ renderMeta(c.meta) }}</span>
                            </div>
                        </div>
                        <div class="contenuto" @click="getCertificate">
                            <div class="stato">
                                <span class="material-symbols-outlined">{{
                                    ['lock','new_releases'][canDownloadCertificate ? 1 : 0] }}</span>
                            </div>
                            <div class="titolo">
                                {{ corso.contents.length + 1 }}. {{ canDownloadCertificate ? 'Scarica il certificato' :
                                'Completa il corso per ottenere il certificato'}}
                            </div>
                            <div class="meta text-muted">

                            </div>
                        </div>
                    </div>


                </div>
                <div class="col-lg-7">
                    <Contenuto v-if="contenutoSelezionato !== null" :coursePermalink="route.params.permalink" :data="corso.contents[contenutoSelezionato]"
                        :autoOpenPdf="autoOpenPdf" @opened="setContentAsOpened(contenutoSelezionato, true)" />

                    <!-- <div class="mt-3">
                    <h3 class="title">{{ corso.contents[contenutoSelezionato].title }}</h3>
                    <div class="text-muted description">
                        <p class="mb-2">{{ corso.contents[contenutoSelezionato].description.split("|")[0] }}</p>
                        <p class="p-0 mt-0">{{ corso.contents[contenutoSelezionato].description.split("|")[1] }}</p>
                    </div>
                </div> -->
                </div>
            </div>
        </section>
        <section v-if="showRelated" class="mt-5">
            <h2>Ti potrebbero interessare</h2>

            <SliderContenuti @emptySlider="() => showRelated=false" :showRelated="false"
                :sliderID="`__related:${route.params.permalink}__`" />
        </section>

    </div>

</template>
<style lang="scss" scoped>


.top {

    position: relative;
    background-color: rgba(var(--bs-body-color-rgb), .04);
    border-bottom: 3px solid var(--bs-body-color);

    h1 {
        font-weight: bold;
        font-size: 32px;
    }

    .image {
        width: 100%;
    }

    .actions {
        margin-top: .5rem;
        display: flex;
        justify-content: flex-end;
        gap: .3rem;
    }

    .descrizione {

        color: var(--bs-secondary-color);

        div:not(:first-child) {
            margin-top: 10px;
        }
    }

    .meta {
        display: block;
        font-size: 13px;
        font-weight: 300;
        gap: .5rem;
        color: var(--bs-secondary-color);
        
        div {
            margin-bottom: .5rem;
        }

        a {
            color: var(--bs-primary)!important;
            font-weight: 500;
            text-decoration: none;

            &:hover {
                text-decoration: underline!important;
            }

        }   

        .topic-theme, .meta-row {
                span:not(:last-child)::after {
                    content: '·';
                    margin: 0 .3rem;
                }
            }
    }

    .toggle {
        display: block;
        position: absolute;
        bottom: 1rem;
        right:  1rem;
        width: 1rem;
        height: 1rem;
        border-radius: 0;
        background: none;
        border: 0;
        outline: 0;
    }

    &.minimized {
        cursor: s-resize;
        position: sticky;
        border-bottom: 2px solid rgba(var(--bs-body-color-rgb), .25);
        border-left: 2px solid rgba(var(--bs-body-color-rgb), .25);
        border-right: 2px solid rgba(var(--bs-body-color-rgb), .25);
        background: var(--bs-body-bg);
        top: 0;
        z-index: 100;

        .right {
            display: none;
        }

        .left {
            flex: 1!important;
            padding: .5rem!important;


            h1 {
                font-size: 18px;
                margin: 0;
                padding: 0;
            }

            .descrizione, .meta {
                display: none!important;

            }

            
        }
    }
}


.contenuti {
    .contenuto {

        display: flex;
        width: 100%;
        padding: .6rem 0 .4rem .6rem;
        border-top: 1px solid rgba(var(--bs-body-color-rgb), .2);
        cursor: pointer;
        align-items: center;

        &:hover {
            background-color: rgba(var(--bs-body-color-rgb), .04);
        }

        &.selected {
            border: 1px solid rgba(var(--bs-body-color-rgb), .2);
            
            font-weight: bold;
            background-color: rgba(var(--bs-body-color-rgb), .04);

            &:not(:last-child) {
                border-bottom: 0;
            }
        }

        .stato {
            padding: 0;

            * {
                display: block;
            }
        }

        .titolo {
            flex-grow: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 13px;
        }

        .meta {

            display: flex;
            gap: .3rem;
            white-space: nowrap;
            font-size: 13px;
            min-width: 75px;

            .material-symbols-outlined {
                display: block;
            }

        }

       
    }
}

.title {
    font-size: 20px;
    font-weight: bold;
}

.description {
    font-size: 13px;
}

</style>