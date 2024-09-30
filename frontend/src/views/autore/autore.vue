<script setup>
import SliderContenuti from '@/components/SliderContenuti.vue';
import {reactive, ref} from 'vue';
import {useRoute} from 'vue-router'
import { request } from '@/utils/request';

const route = useRoute()
const author = reactive({})

const bibliographyExpand = ref(false);

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

                    <div class="col-lg-5 biblio-wrapper" :class="{'less' : author.bibliography.length > 100 && !bibliographyExpand}">
                        <h3>Bibliografia</h3>

                        <div class="bibliography" v-html="author.bibliography"></div>
                    </div>
                    <div v-if="author.bibliography.length > 100 && !bibliographyExpand" class="read-more">
                        <a href="#" @click="bibliographyExpand = true">Leggi tutto...</a>
                    </div>
                    <div v-if="author.bibliography.length > 100 && bibliographyExpand" class="read-more">
                        <a href="#" @click="bibliographyExpand = false"><span class="material-symbols-outlined" style="vertical-align: bottom;">arrow_upward</span> Comprimi</a>
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
<style lang="scss">
.bibliography p {
    margin-bottom: 0;
}
</style>
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

    .biblio-wrapper.less {
        max-height: 300px;
        overflow:hidden;
        position: relative;

        &::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px; /* Height of the fade effect */
            background: linear-gradient(to bottom, rgba(246, 246, 247, 0) 0%, rgba(246, 246, 247, 1) 100%);
            pointer-events: none; /* Prevent interaction with the pseudo-element */
        }

    }

    .read-more {
        font-size: 13px;
        text-align: right;
        a {
            text-decoration: none;
        }
       
    }

    .bibliography {
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

@media (max-width: 768px) {
    .top {
        flex-direction: column;
    }

    .biblio-wrapper {
        margin-top: 1rem;

        h3 {
            font-size: 18px;
        }
    }
}
</style>
<style lang="scss">
@media (max-width: 768px) {
    .biblio-wrapper {
        ul, ol {
            padding-left: 0!important;
        }
    }
}
</style>