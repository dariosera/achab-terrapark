<script setup>
import {Splide, SplideSlide, SplideTrack} from '@splidejs/vue-splide'
import Banner from './Banner.vue';
import SliderContenuti from '@/components/SliderContenuti.vue'
import {ref} from 'vue'
import { request } from '@/utils/request';

import SliderTemi from './SliderTemi.vue';


const bannerContent = ref([]);
request({
    task : "homepage/getSlides",
    callback : function(dt) {
        bannerContent.value = dt;
    }
})

// const bannerContent = [
//     {
//         title : "Sai che ogni anno un cittadino europeo produce circa 500 kg di rifiuti?",
//         content : "Buona parte di questi rifiuti può essere evitata o riutilizzata attraverso semplici strategie",
//         image : "https://cidiu.scuolapark.it/imgs/02.2890af9c31444f07c44908ca99b1ca06.jpg",
//         action : {
//             text : "Scopri di più",
//             href : "https://www.google.com"
//         }
//     },
//     {
//         title : "Vuoi un giardino più verde e rigoglioso e insieme ridurre drasticamente i tuoi rifiuti domestici?",
//         content : "Il compostaggio domestico è la soluzione!",
//         image : "https://cidiu.scuolapark.it/imgs/02.2890af9c31444f07c44908ca99b1ca06.jpg",
//         action : {
//             text : "Scopri di meno",
//             href : "https://www.achabgroup.it"
//         }
//     },
//     {
//         title : "È ancora possibile garantire un futuro sostenibile alla nostra specie?",
//         content : "La storia dell’evoluzione non è solo un passato lontano, ma una lezione per il presente e il futuro",
//         image : "https://cidiu.scuolapark.it/imgs/02.2890af9c31444f07c44908ca99b1ca06.jpg",
//         action : {
//             text : "Iscriviti",
//             href : "./catalogo"
//         }
//     }
// ]

const splideOptions = {
    type: 'loop',
    autoplay: true,
    pagination: false,
    interval: 8000
}


</script>
<template>
    <div class="container">


        <Splide v-if="bannerContent.length > 0" :has-track="false" :options="splideOptions">
            <SplideTrack>
                <SplideSlide v-for="(bC, i) in bannerContent " :key="i">
                    <Banner :slide="bC" />
                </SplideSlide>
            </SplideTrack>

            <div class="splide__progress">
                <div class="splide__progress__bar">
                </div>
            </div>
        </Splide>

        <section class="with-top-border">
            <h2>{{$t('home.iTuoiCorsi')}}: {{ $t('home.continuaAdImparare') }}</h2>
            <SliderContenuti  sliderID="__myCourses__" />
        </section>

        <section class="with-top-border">
            <h2>{{$t('home.vistiDiRecente')}}</h2>

            <SliderContenuti  sliderID="__history__"/>

        </section>





        <section class="with-top-border">
            <h2>Tutti i corsi</h2>

            <SliderContenuti sliderID="__courses__" />
        </section>


       <SliderTemi />

    </div>
</template>
<style lang="scss" scoped>

.grid-anteprime {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
    margin: -1rem;
}



</style>