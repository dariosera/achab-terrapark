<script setup>

import Anteprima from '@/components/Anteprima.vue';
import SkeletonAnteprima from '@/components/SkeletonAnteprima.vue';

//import anteprimaCasuale from '@/fake_data/anteprimaCasuale'
import {Splide, SplideSlide} from '@splidejs/vue-splide'
import {ref,reactive, onMounted} from 'vue';
import { request } from '@/utils/request';
import { useTerraParkStore } from '@/stores/commons';

const tps = useTerraParkStore()

const w = ref(null)

const urlParams = new URLSearchParams(window.location.search);
const widgetID = urlParams.get("id");
const emit = defineEmits(["emptySlider"]);

const contenutoSelezionato = ref(null);
const tagDettaglio = ref(null)

const contenuti = ref([])
const waiting = ref(true);
const title = ref("")

tps.init()

const style = reactive({
    width: null,
    height: null
})

const sliderOptions = reactive({})

onMounted(() => {
    if (urlParams.has("width")) {
        const width = parseInt(urlParams.get("width"))
        style.width = width + "px";
        console.log("Set width")
    }

    if (urlParams.has("height")) {
        const height = parseInt(urlParams.get("height"))
        style.height = height + "px";
    }

    Object.assign(sliderOptions,{
        perPage: 4,
        pagination: false,
        classes : {
            prev  : 'splide__arrow--prev arrow-prev-outside',
            next  : 'splide__arrow--next arrow-next-outside',
        },
        gap: "1rem",
        breakpoints: {
                1400: {
                    perPage : 3,
                },
                992: {
                    perPage: 2,
                },
                768: {
                    perPage: 1,
                },
                576: {
                    perPage: 1,
                }
        }
    })
})

request({
    task : "public/widget",
    data : {
        widgetID,
    },
    callback : function(dt) {
        waiting.value = false
        contenuti.value = dt.contents;

        title.value = dt.title;
    }
})



</script>
<template>
    <div class="p-5" ref="w" :style="style">
        <h5 v-if="!(urlParams.has('title') && urlParams.get('title') == 'false')" class="title">{{ title }}</h5>
        <template v-if="contenuti.length === 0">
            <Splide :options="sliderOptions" v-if="waiting">
                <SplideSlide v-for="i in [0,1,2,3]" :key="i" class="py-3">
                    <SkeletonAnteprima />
                </SplideSlide>
            </Splide>
            <div v-else>
                Nessun contenuto
            </div>
        </template>
        <template v-else>
            <Splide :options="sliderOptions">
                <SplideSlide v-for="(c,i) in contenuti" :key="i" class="py-3">
                    <Anteprima :data="c" :isPublic="true" />
                </SplideSlide>
            </Splide>
        </template>
    </div>
</template>