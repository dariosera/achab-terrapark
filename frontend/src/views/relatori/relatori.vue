<script setup>
import SliderContenuti from '@/components/SliderContenuti.vue';
import { request } from '@/utils/request';
import {ref} from 'vue'

const authors = ref([])

request({
    task : "authors/get",
    callback : (dt) => {
        authors.value = dt;
    }
})

</script>
<template>
   
<div class="container">
    <section class="mt-3">
        <h1>Relatori</h1>
        <div  class="grid-relatori">
            <RouterLink :to="`/relatore/${author.authorID}`" v-for="(author,i) in authors" :key="author.authorID">
                <div class="relatore shadow-sm">
                    <img :src="author.picture_url">
        
                    <h2 class="mt-2">{{ author.name }} {{ author.surname }}</h2>
                    <div class="role">{{ author.role }}</div>
                </div>
            </RouterLink>
        </div>
    </section>
</div>

</template>
<style lang="scss" scoped>

.grid-relatori {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 200px));
    gap: 2rem; /* Adjust gap as needed */


    a {
        text-decoration: none;
        transition: background-color .2s ease-in-out;

        &:hover {
            background-color: rgba(var(--bs-body-color-rgb),.03);
            box-shadow: var(--bs-box-shadow) !important;
        }
    }

    .relatore {
        width: 200px;
        padding: 1rem;
        cursor: pointer;

        img {
            display: block;
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border: 0;
            outline: none;
            background-color: rgba(var(--bs-body-color-rgb),.05)
        }

        h2 {
            font-size: 16px;
            font-weight: bold;
            color: var(--bs-body-color);
            margin-bottom: 0;
        }

        .role {
            font-size: 12px;
            color: rgba(var(--bs-body-color-rgb),.7);
            text-transform: uppercase;
        }
    }
}
</style>