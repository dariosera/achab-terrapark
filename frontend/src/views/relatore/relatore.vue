<script setup>
import SliderContenuti from '@/components/SliderContenuti.vue';
import {reactive} from 'vue';
import {useRoute} from 'vue-router'
import { request } from '@/utils/request';

const route = useRoute()
const author = reactive({})

request({
    task : "authors/single",
    data : {
        authorID : route.params.id
    },
    callback: (dt) => {
        Object.assign(author,dt)
    }
})

</script>
<template>
    <div class="container">

        <div class="row top">

            <div class="col-auto">
                <img class="foto" :src="author.picture_url">
            </div>
            <div class="col">

                <div class="row">

                    <div class="col-lg-7">
                        <h1 class="nome">{{author.name}} {{ author.surname }}</h1>
                        <h5 class="ruolo">{{author.role}}</h5>

                        <div class="bio">{{ author.bio }}</div>
                    </div>

                    <div class="col-lg-5">
                        <h3>Bibliografia</h3>

                        <span class="bg-warning">--- --- --- TODO BIBLIOGRAFIA --- --- --- </span>

                        <ul class="elenco-bibliografia text-muted">
                            <li> Lorem ipsum dolor sit amet</li>
                            <li> Lorem ipsum dolor sit amet</li>
                            <li> Lorem ipsum dolor sit amet</li>
                            <li> Lorem ipsum dolor sit amet</li>
                        </ul>
                    </div>

                </div>

            </div>

            <div></div>

            
            <div class="col-lg-6 mt-5">
                <h3>Argomenti trattati</h3>

                <div class="tag-list">
                    <span v-for="tag in author.tags" :key="tag.tagID" class="badge badge-pill bg-dark me-1">{{tag.description}}</span>
                </div>
            </div>

        </div>

        <section class="mt-5">
            <h2>I contenuti di {{ author.name + " " + author.surname }}</h2>

            <SliderContenuti :sliderID="`__author:${route.params.id}__`" />
        </section>
    </div>

</template>
<style lang="scss" scoped>
.top {

    background-color: rgba(var(--bs-body-color-rgb), .04);
    border-bottom: 3px solid var(--bs-body-color);
    padding: 2rem;

    .nome {
        font-weight: bold;
        font-size: 28px;
        margin-bottom: 0;
    }

    .ruolo {
        margin-top: 0;
        font-size: 15px;
    }

    .bio {
        font-size: 13px;
        color: var(--bs-secondary);
    }

    .foto {
        display: block;
        width: 100%;
        max-width: 150px;
        aspect-ratio: 1;
        background-color: rgba(var(--bs-body-color-rgb),.05);
        object-fit: cover;
    }

    h3 {
        font-weight: bold;
        font-size: 26px;
    }

    .elenco-bibliografia {
        font-size: 14px;
    }
}

</style>