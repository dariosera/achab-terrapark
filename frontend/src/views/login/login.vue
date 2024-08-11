<script setup>
import { setToken } from '../../utils/auth';
import { request } from '../../utils/request';
import {reactive, inject} from 'vue';
import { useRouter } from 'vue-router';
import {GoogleLogin} from 'vue3-google-login'
import { useProjectStore } from '@/stores/project';
import { useTerraParkStore } from '@/stores/commons';

let email = "", password = "";

const errorMessage = reactive({text: null});
const ps = useProjectStore()
const tps = useTerraParkStore()

const features = reactive({});
request({
    task : "core.public.getFeatures",
    data : { features : ["signup","loginWithGoogle","loginWithGoogleOnly"] },
    callback : function(dt) {
        Object.assign(features,dt)
    }
})

const router = useRouter();
const login = (e) => {
    e.submitter.disabled = true;

    request({
        task : "core.public.login",
        data : {
            email, password
        },
        callback : function(data) {

            setToken(data.jwt);
            //setUserInfo(data.userinfo);

            // toasts.addToast({
            //     title : `<i class="bi bi-emoji-smile"></i> Accesso effettuato`,
            // })

            tps.init();

            const after_login_redirect = sessionStorage.getItem("sspa_after_login_redirect");
            if (after_login_redirect !== null) {
                router.push({path: after_login_redirect})
            } else {
                router.push({path : "/"})
            }
            e.submitter.disabled = false;   
        },
        error : function(err) {
            e.submitter.disabled = false;   
            
            errorMessage.text = err.error;
            
        }
    })
    
}

const handleGoogleCallback = (res) => {

    request({
        task : "core.public.googleLogin",
        data : {
            token : res.credential,
        },
        callback : function(data) {
            setToken(data.jwt);
            //setUserInfo(data.userinfo);

            // toasts.addToast({
            //     title : `<i class="bi bi-emoji-smile"></i> Accesso effettuato`,
            // })

            const after_login_redirect = sessionStorage.getItem("sspa_after_login_redirect");
            if (after_login_redirect !== null) {
                router.push({path: after_login_redirect})
            } else {
                router.push({path : "/"})
            }
            e.submitter.disabled = false;   
        },
        error : function(err) {          
            errorMessage.text = err.error;
        }
    })
}

</script>
<template>
    <div class="container-fluid">
        
            <div class="card login-card">
                <div class="card-body">

                    <div class="text-center"><img :src="ps.getTheme().logo.image_url"></div>

                    <h5 class="card-title text-center">Accedi a <b>{{ ps.getTitle() }}</b></h5>
                    <form v-if="!features.loginWithGoogleOnly" @submit.prevent="login">
                        <div class="form-group">
                            <label for="email">Username</label>
                            <input required v-model="email" type="email" class="form-control" id="username" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input required v-model="password" type="password" class="form-control" id="password" placeholder="Password">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="persistent">
                            <label class="form-check-label" for="persistent">Resta collegato</label>
                        </div>


                        <button type="submit" class="btn btn-primary mt-3">Effettua l'accesso</button>

                        
                    </form>

                    <div class="my-5 text-center" v-if="(features.loginWithGoogle || features.loginWithGoogleOnly)">
                        <GoogleLogin clientId="265242670424-qp64siqcjtuku5v6uvvmabsq4omvtk1t.apps.googleusercontent.com" :callback="handleGoogleCallback"></GoogleLogin>
                    </div>

                    

                    <div v-if="errorMessage?.text" class="alert alert-danger mt-2">{{ errorMessage.text }}</div>
                    

                </div>
                <div class="card-footer helper-sso" v-if="features.loginWithGoogleOnly">
                        Effettua l'accesso con il tuo account Google o aziendale.<br>
                        <span v-if="features.signup">Se non sei ancora registrato, il tuo account verrà creato automaticamente.</span>
                    </div>
                <div class="card-footer" v-if="!features.loginWithGoogleOnly">
                    <RouterLink v-if="features.signup" to="/registrati">Registrati</RouterLink>
                    <RouterLink to="/recupero-password">Recupera la password</RouterLink>
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

.container-fluid {
    background: linear-gradient(135deg, #ff7e5f, #feb47b);
    
    /* For fallback */
    background-color: #ff7e5f;
}


.helper-sso {
    font-size: .8rem;
    color: var(--bs-text-body-tertiary);
}

.login-card {
    
    width: 100%;
    max-width: 400px;
    min-width: 300px;
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