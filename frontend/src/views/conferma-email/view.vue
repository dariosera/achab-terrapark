<script setup>
import {useRoute, useRouter} from 'vue-router'
import { request } from '@/utils/request';
import useToasts from '../../stores/toasts';
import { reactive } from 'vue';

const route = useRoute();
const router = useRouter();
const errorMessage = reactive({text: false})

request({
    task : "core.public.confermaEmail",
    data : {uid : route.params.uid, vid: route.params.vid},
    callback : function(dt) {
        useToasts().addToast({
            title : "Perfetto!",
            content: "Hai confermato correttamente la tua email",
        })

        router.push({path: "/"})

    },
    error : function(e) {
        errorMessage.text = e.error;
    }
})

</script>
<template>
    <div class="container p-5">
        <h1>Conferma indirizzo email</h1>
        <div class="alert alert-danger" v-if="errorMessage.text">{{ errorMessage.text }}</div>
        <div v-else>Attendere...</div>

        <router-link to="/">Torna alla home</router-link>
    </div>
</template>
<style lang="scss" scoped>

@media (min-width: 992px) {
    .container-fluid {
        display: grid;
        place-items: center;
        min-height: 100vh;
    }

}

.login-card {
    
    width: 100%;
    max-width: 300px;
    margin: 0 auto;

    .form-group {
        margin: 1rem 0;
    }

    .card-footer {
        a {
            margin-right: 0.5rem;
        }
    }
}
</style>