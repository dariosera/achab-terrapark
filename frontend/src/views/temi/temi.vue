<script setup>

import {ref} from 'vue'
import { request } from '@/utils/request';
import { useRoute } from 'vue-router';
import getTemi from '@/fake_data/temi';

const route = useRoute()
const temi = ref([])

request({
    task : "themes/getWithDetails",
    callback: (dt) => {
        temi.value = dt;
        temi.value.forEach((tema,i) => {
            import(`../../assets/theme_icons/${tema.url}.svg`).then(img => {
                temi.value[i].url_img = img.default;
            })
        })

        setTimeout(() => {
            if (route.hash.length > 0) {
                console.log(route.hash)
                document.querySelector(route.hash)?.scrollIntoView({behavior: 'smooth'}) 
            }
        }, 150)
       
        
    }
})



</script>
<template>
    <div class="container">
        <section class="mt-3">

            <h1>Temi</h1>

            <p><span class="bg-warning">--- --- --- TODO TESTO INTRO TEMI --- --- --- </span></p>

            <div class="" id="elenco-temi">
                <div class="tema" v-for="tema in temi" :key="tema.id" :id="tema.url">

                    <div class="row">
                        <div class="col-auto">
                            <div class="icona"><img :src="tema.url_img"></div>
                        </div>
                        <div class="col info-tema">
                            <h2>{{ tema.title }}</h2>
                            <div>{{ tema.description }}</div>
                        </div>
                    </div>
                    <div class="mt-2">{{ tema.long_description }}</div>
                </div>
            </div>


        </section>
    </div>


</template>
<style scoped lang="scss">

.tema {
    border: 1px solid rgba(var(--bs-body-color-rgb),.15);
    padding: 2rem;
    margin-bottom: 2rem;
}

.icona {
    img {
        height: 6rem;
        aspect-ratio: 1;
        display: block;
    }

    background: rgba(0,0,0,.09);
    padding: 1rem;
    
}

.info-tema {
    align-content: center;
}

h2 {
    font-size: 20px;
    font-weight: bold;
}
</style>