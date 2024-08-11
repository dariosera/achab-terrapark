<script setup>

import Anteprima from './Anteprima.vue';
import SkeletonAnteprima from './SkeletonAnteprima.vue';
//import anteprimaCasuale from '@/fake_data/anteprimaCasuale'
import {Splide, SplideSlide} from '@splidejs/vue-splide'
import {ref} from 'vue';
import Dettaglio from './Dettaglio.vue'
import { request } from '@/utils/request';

const props = defineProps(["showRelated","sliderID"]);

const emit = defineEmits(["emptySlider"]);

const contenutoSelezionato = ref(null);
const tagDettaglio = ref(null)

const contenuti = ref([])
const waiting = ref(true);

request({
    task : "contents/slider",
    data : {
        sliderID : props.sliderID
    },
    callback : function(dt) {
        waiting.value = false
        contenuti.value = dt;

        if (dt.length === 0) {
            emit("emptySlider")
        }
    }
})

const sliderOptions = {
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
}


const mostraDettaglio = function(i) {
    contenutoSelezionato.value = contenuti.value[i];
    window.scrollTo({
        top: tagDettaglio.value.offsetTop,
        behavior : 'smooth'
    })
}
</script>
<template>
    <div>
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
                    <Anteprima :data="c" @mostraDettaglio="mostraDettaglio(i)" />
                </SplideSlide>
            </Splide>
        </template>
       
        <div ref="tagDettaglio" class="dettaglio" >
            <Dettaglio v-if="contenutoSelezionato !== null" :data="contenutoSelezionato" @close="() => {contenutoSelezionato = null}" :showRelated="props?.showRelated" />
        </div>
    </div>
</template>