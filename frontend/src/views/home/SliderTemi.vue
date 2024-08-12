<script setup>

import {Splide, SplideSlide, SplideTrack} from '@splidejs/vue-splide'
import {ref, onMounted} from 'vue'
import { request } from '@/utils/request';
import { useTerraParkStore } from '@/stores/commons';


const tps = useTerraParkStore();

const temaSelezionato = ref(null)

const temi = ref([])

onMounted(async () => {
    temi.value = await tps.getThemes()
})

// request({
//     task : "themes/get",
//     callback : (dt) => {
//         temi.value = dt
//     }
// })

const splideOptionsTemi = {
    perPage : 5,
    gap: '1rem',
    pagination: false,
    classes : {
    prev  : 'splide__arrow--prev arrow-prev-outside',
	next  : 'splide__arrow--next arrow-next-outside',
  },
  breakpoints : {
    1400: {
            perPage : 4,
        },
        992: {
            perPage: 3,
        },
        576: {
            perPage: 2,
        }
  }
}

const selezionaTema = (i) => {
    temaSelezionato.value = i
}
</script>
<template>
    <section class="with-top-border">

        <h2>{{ $t('common.temi') }}</h2>

        <div class="grid-temi">

            <template v-if="temi.length > 0">
                <Splide :options="splideOptionsTemi">
                    <SplideSlide v-for="(tema, i) in temi" :key="i" class="tema"
                        :class="{ 'selected': i === temaSelezionato }" @click="selezionaTema(i)">
                        <div class="icona"><img :src="tema.url_img"></div>
                        <h3>{{ tema.title }}</h3>
                    </SplideSlide>
                </Splide>
            </template>
            <template v-else>
                <Splide :options="splideOptionsTemi">
                    <SplideSlide v-for="i in [0,1,2,3]" :key="i" class="tema">
                        <div class="icona skeleton"></div>
                        <h3 class="skeleton"></h3>
                    </SplideSlide>
                </Splide>
            </template>


        </div>
        <div class="dettagli-tema" v-if="temaSelezionato !== null">
            <div>{{ temi[temaSelezionato].description }}</div>
            <div>
                <RouterLink :to="`/temi#${temi[temaSelezionato].url}`" class="more">Scopri di più</RouterLink>
            </div>
        </div>
    </section>
</template>
<style lang="scss" scoped>

.grid-temi {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;

    .tema {
        color: var(--bs-body-color);
        text-decoration: none;
        cursor: pointer;

        .icona {
            img {
                width: 100%;
                aspect-ratio: 1;
                display: block;
            }

            padding: 1rem;
    
            width: 100%;
            aspect-ratio: 1;
            background-color: rgba(var(--bs-body-color-rgb), .05);
            background-size: 80% auto;
            background-position: center center;
            background-repeat: no-repeat;
            transition: all .3s ease-in-out;

            &:hover {
                background-color: rgba(var(--bs-body-color-rgb), .08);
            }
        }

        h3 {
            font-size: 1rem;
            margin-top: .5rem;
            font-weight: bold;
            transition:all .2s ease-in-out;
            color: rgba(0,0,0,.7);
            line-height: 20px;
            height: 20px;
        }

        &.selected h3 {
            color: #000;
        }


       

        &.selected .icona {
            background-color: rgba(var(--bs-body-color-rgb), .15);
            transform: scale(1.05);
        }

        
    }

}

.dettagli-tema {
        .more {
            display: block;
            margin-top: .5rem;
            text-transform: uppercase;
            color: var(--bs-body-color);
            font-weight: bold;
        }
    }

@media screen and (max-width: 768px) {
   .grid-temi {
        padding: 0 2.5rem;
   }
}
</style>