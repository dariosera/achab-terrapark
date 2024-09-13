<script setup>
import { request } from '@/utils/request';
import {reactive} from 'vue';
import useModals from '../../stores/modals';
import { useRouter, useRoute } from 'vue-router';

const d = reactive({pwd: null, pwd2: null})
const errorMessage = reactive({text: null})
const router = useRouter();
const route = useRoute();

const reimposta = function() {

    if (d.pwd !== d.pwd2) {
        errorMessage.text = "Le due password non coincidono.";
        return;
    }

    request({
        task : "core.public.impostaPassword",
        data : {
            uid: route.params.uid,
            rid: route.params.rid,
            pwd : d.pwd
        },
        callback: (dt) => {
            useModals().msgbox({
                title : "Operazione completata",
                content: `Puoi effettuare l'accesso con la password appena scelta.`
            });

            router.push({path: "/login"})

        },
        error: (e) => {
            errorMessage.text = e.error;
        }
    })

}

</script>
<template>
    <div class="container-fluid">
        
            <div class="card login-card">
                <div class="card-body">

                    <h5 class="card-title">Reimposta la password del tuo account</h5>
                    <form @submit.prevent="reimposta">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input required v-model="d.pwd" type="password" class="form-control" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="password">Ripeti password</label>
                            <input required v-model="d.pwd2" type="password" class="form-control" placeholder="">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-arrow-right"></i> Continua</button>

                        <div v-if="errorMessage?.text" v-html="errorMessage.text" class="alert alert-danger mt-2"></div>
                    </form>
                </div>
                <div class="card-footer">

                    <RouterLink to="/login">Torna al login</RouterLink>
                </div>
            </div>
        
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