<script setup>
import { defineProps, reactive, ref, watch, onMounted} from 'vue';
import Anteprima from './Anteprima.vue';
import Actions from './Actions.vue';
import Tags from './Tags.vue';
import Author from './Author.vue';
import {Splide, SplideSlide} from '@splidejs/vue-splide'
import anteprimaCasuale from '@/fake_data/anteprimaCasuale';
import Contenuto from './Contenuto.vue';
import { useTerraParkStore } from '@/stores/commons';
import { request } from '@/utils/request';

const tps = useTerraParkStore()

const props = defineProps(["data","showRelated","redirect_host"])

const correlati = ref([])
// if (props?.showRelated !== false)
// for (let i=0; i<Math.random()*6; i++) {
//     correlati.value.push(anteprimaCasuale())
// }
// correlati.value.forEach((c) => {c.selected = false})


const tagDettaglio = ref(null)

const guardaCorrelato = ref(false)

const contenuto = reactive(Object.assign({},props.data))

const mostraDettaglio = (c) => {
    Object.assign(contenuto, c)
    guardaCorrelato.value = true;
    correlati.value.forEach(correlato => {
        if (correlato.permalink == c.permalink)
            correlato.selected = true;
        else 
            correlato.selected = false
    })
    window.scrollTo({
        top: tagDettaglio.value.offsetTop,
        behavior : 'smooth'
    })
}


const mostraOriginale = () => {
    Object.assign(contenuto, {})
    Object.assign(contenuto, props.data)
    getMedia()
    guardaCorrelato.value = false;
    correlati.value.forEach(correlato => {
       correlato.selected = false
    })
    window.scrollTo({
        top: tagDettaglio.value.offsetTop,
        behavior : 'smooth'
    })
}

const sliderOptions = {
  perPage: 4,
  pagination: false,
  classes : {
    prev  : 'splide__arrow--prev arrow-prev-outside',
	next  : 'splide__arrow--next arrow-next-outside',
  },
  gap: "15px",
  breakpoints: {
        1700 : {
            perPage : 3,
        },
        1400: {
            perPage: 2
        },
        900: {
            perPage: 1,
        }
        // },
        // 992: {
        //     perPage: 2,
        // },
		// 768: {
		// 	perPage: 1,
		// },
        // 576: {
        //     perPage: 1,
        // }
  }
}

watch(() => props.data,() => {
    console.log("updated")
    mostraOriginale()
}, {deep: true} )


const renderMeta = (meta) => {
    if (meta?.duration) {
        return `Durata: ${Math.ceil(meta.duration/60)} min.`;
    }

    if (meta?.pages) {
        return `${meta.pages} pagin${meta.pages==1?'a':'e'}`;
    }

    return ``
}

function goToTerrapark() {

    let url = `https://${props.redirect_host}/`
        if (props.data.isCourse) {
            url += `corso/${props.data.permalink}`
        } else {
             url += `watch/${props.data.permalink}`
        }

        window.open(url)
        return;
}
</script>
<template>
    <div ref="tagDettaglio" class="row dettaglio" @click="goToTerrapark">
        <div class="col-lg-6">
            <img class="image" :src="contenuto.image">
            <div class="text-end mt-2">
            </div>
        </div>
        <div class="col-lg-6">
            <div v-if="guardaCorrelato" class="mb-2">
                <button class="btn btn-sm btn-outline-primary d-flex" @click="mostraOriginale"><span
                        class="material-symbols-outlined">keyboard_arrow_left</span> Torna a&nbsp;<b>{{
                        props.data.title.substring(20) }}...</b></button>
            </div>
            <h3>{{ contenuto.title }}</h3>

            <div class="descrizione">
                <p>{{ contenuto.description.split('|')[0] }}</p>
                <div class="mt-1">{{ contenuto.description.split('|')[1] }}</div>
            </div>

            <div class="meta mt-3">

                <div class="topic-theme">
                    <span v-if="contenuto.themeID !== null ">Tema: <strong><router-link :to="`/catalogo?themes=${contenuto.themeID}`">{{ tps.getTheme(contenuto.themeID).title }}</router-link></strong></span>
                    <span v-if="contenuto.topicID">Argomento: <strong><router-link :to="`/catalogo?topics=${contenuto.topicID}`">{{ tps.getTopic(contenuto.topicID).title }}</router-link></strong></span>
                </div>

                <div>{{ renderMeta(contenuto.meta) }}</div>

                <div v-if="contenuto.tags.length > 0">
                    Tag: 
                    <span v-for="tag,i in contenuto.tags" :key="i" class="badge border text-muted px-2 me-2">{{ tag.description }}</span>
                    
                </div>

                <Author :authors="contenuto.authors"/>

                
            </div>
        </div>
    </div>
</template>
<style lang="scss" scoped>

a {
    font-weight: 500;
    color: var(--bs-primary)!important;

    &:hover {
        text-decoration: underline!important;
    }
}

.dettaglio {
    cursor: pointer;
    padding: .25rem;
    margin-top: 1rem;
    position: relative;

    .skeleton-contenuto {
        aspect-ratio: 16/9;
    }

    h3 {
        font-size: 19px;
        user-select: none;
    }

    .descrizione {
        font-size: 13px;
        color: var(--bs-secondary);
        user-select: none;

    }

    .meta {
        display: block;
        font-size: 13px;
        font-weight: 300;
        gap: .5rem;
        color: var(--bs-secondary-color);

        div {
            margin-bottom: .4rem;
        }

        .topic-theme {
            span:not(:last-child)::after {
                content: '·';
                margin: 0 .5rem;
            }
        }

        strong, strong a {
            color: var(--bs-body-color);
            text-decoration: none;

            a:hover {
                text-decoration: underline;
            }

        }   
    }

    .image {
        width: 100%;
    }

    .btn-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    .actions {

        display: flex;
        justify-content: flex-end;
        gap: .3rem;

        button {
                background: none;
                border: 0;
                outline: 0;
                margin: 0;
                padding: 0;
            }
    }

}
.slider-suggeriti {
    display: flex;
}
</style>