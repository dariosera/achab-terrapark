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

const props = defineProps(["data","showRelated"])

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

request({
    task : "content/getMedia",
    data : {
        permalink : contenuto.permalink
    },
    callback : function(dt) {
        contenuto.media = dt;
    }
})



const mostraOriginale = () => {
    Object.assign(contenuto, {})
    Object.assign(contenuto, props.data)
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
})


const renderMeta = (meta) => {
    if (meta?.duration) {
        return `Durata: ${parseInt(meta.duration/60)} min.`;
    }

    if (meta?.pages) {
        return `${meta.pages} pagin${meta.pages==1?'a':'e'}`;
    }

    return ``
}
</script>
<template>
    <div ref="tagDettaglio" class="row dettaglio">
        <div class="col-lg-6">
            <!-- <img class="image" :src="contenuto.image"> -->
            <Contenuto v-if="contenuto.media" :data="contenuto"></Contenuto>
            <div v-else class="skeleton skeleton-contenuto"></div>
            <div class="text-end mt-2">
                <Actions :permalink="props.data.permalink" />
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

                <div>
                    <span>Tema: <strong><router-link :to="`/catalogo?themes=${tps.getTopic(contenuto.topicID).themeID}`">{{ tps.getTheme(tps.getTopic(contenuto.topicID).themeID ).title }}</router-link></strong></span>
                    &middot;
                    <span>Argomento: <strong><router-link :to="`/catalogo?topics=${contenuto.topicID}`">{{ tps.getTopic(contenuto.topicID).title }}</router-link></strong></span>
                </div>
                {{ renderMeta(contenuto.meta) }}

                <Tags  v-if="contenuto" :permalink="contenuto.permalink"></Tags>

                <Author :authorID="contenuto.authorID"/>

                
            </div>
        </div>
        <div class="col-12" v-if="correlati.length > 0">
            <h5>Ti potrebbero interessare</h5>

            <div class="slider-suggeriti px-3">
                <Splide :options="sliderOptions">
                    <SplideSlide v-for="(correlato, i) in correlati" :key="i">
                        <Anteprima :data="correlato" :selected="correlato?.selected"
                            @mostraDettaglio="mostraDettaglio" />
                    </SplideSlide>
                </Splide>
            </div>


        </div>
        <button @click="$emit('close')" type="button" class="btn-close" aria-label="Close"></button>
    </div>
</template>
<style lang="scss" scoped>

.dettaglio {
    padding: 2rem;
    margin-top: 1rem;
    border:1px solid var(--bs-border-color);
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