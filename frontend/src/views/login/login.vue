<script setup>
import { setToken, AFTER_LOGIN_REDIRECT } from '../../utils/auth';
import { request } from '../../utils/request';
import { reactive, ref, inject } from 'vue';
import { useRouter } from 'vue-router';
import { GoogleLogin } from 'vue3-google-login'
import { useProjectStore } from '@/stores/project';
import { useTerraParkStore } from '@/stores/commons';
import useToasts from '@/stores/toasts';

let email = "", password = "";

const googleClientId = import.meta.env.VITE_GOOGLE_CLIENT_ID;

const errorMessage = reactive({ text: null });
const ps = useProjectStore()
const tps = useTerraParkStore()

const customSignupFields = ref([])
const customSignupFieldsResponses = reactive({})
const step = ref(1)
request({
    task : "public/customSignupFields",
    data : {},
    callback : function(dt) {
        customSignupFields.value = dt;
    }
})


const customTheme = reactive(ps.getTheme())

const features = reactive({});
request({
    task: "core.public.getFeatures",
    data: { features: ["signup", "loginWithGoogle", "loginWithGoogleOnly"] },
    callback: function (dt) {
        Object.assign(features, dt)
    }
})

const router = useRouter();
const login = (e) => {

        e.submitter.disabled = true;

        request({
            task: "core.public.login",
            data: {
                email, password
            },
            callback: function (data) {

                setToken(data.jwt);
                //setUserInfo(data.userinfo);

                // toasts.addToast({
                //     title : `<i class="bi bi-emoji-smile"></i> Accesso effettuato`,
                // })

                tps.init();

                useToasts().addToast({
                    title: "Benvenuto!",
                    content: `Login avvenuto correttamente`
                })

                const after_login_redirect = sessionStorage.getItem(AFTER_LOGIN_REDIRECT);
                if (after_login_redirect !== null) {
                    sessionStorage.removeItem(AFTER_LOGIN_REDIRECT);
                    router.push({ path: after_login_redirect })
                } else {
                    router.push({ path: "/" })
                }
                e.submitter.disabled = false;
            },
            error: function (err) {
                e.submitter.disabled = false;
                errorMessage.text = err.error;

            }
        })
   
    

}

function saveCustomFields() {
    request({
            task : "profile/saveCustomFieldsResponses",
            data : {customSignupFields: customSignupFieldsResponses},
            callback : function(dt) {
                const after_login_redirect = sessionStorage.getItem(AFTER_LOGIN_REDIRECT);
                if (after_login_redirect !== null) {
                    router.push({ path: after_login_redirect })
                } else {
                    router.push({ path: "/" })
                }
            }
        })
        
}

const handleGoogleCallback = (res) => {

    request({
        task: "core.public.googleLogin",
        data: {
            token: res.credential,
        },
        callback: function (data) {

            setToken(data.jwt);
            //e.submitter.disabled = false;

            if (data.newUser) {
                // Deve completare i customFields
                step.value = 2;
            } else {
                const after_login_redirect = sessionStorage.getItem(AFTER_LOGIN_REDIRECT);
                if (after_login_redirect !== null) {
                    router.push({ path: after_login_redirect })
                } else {
                    router.push({ path: "/" })
                }
            }

            

            
            
        },
        error: function (err) {
            errorMessage.text = err.error;
        }
    })
}

</script>
<template>
    <div class="container-fluid" :style="{'background-color' : customTheme.loginPage.background}">

        <div class="login-card">
                    <div class="banner-left"  :style="{'background-color' : customTheme.loginPage.cardBackground}">

                        <div class="logo bg-white"><img :src="customTheme.logo.image_url"></div>

                        <div class="banner-left-content">
                            <h2 v-html="customTheme.loginPage.welcomeText"></h2>
                            <img class="banner-image" src="@/assets/graficalogin.jpg">
                        </div>
                    </div>
                    <div class="login-area">

                        <div v-if="step == 1">
                            <h1>Accedi al tuo account</h1>
                            <form v-if="!features.loginWithGoogleOnly" @submit.prevent="login">
                                <div class="form-group">
                                    <label for="email">Username</label>
                                    <input required v-model="email" type="email" class="form-control" id="username"
                                        placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input required v-model="password" type="password" class="form-control" id="password"
                                        placeholder="Password">
                                </div>
                                <!-- <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="persistent">
                                    <label class="form-check-label" for="persistent">Resta collegato</label>
                                </div> -->
                                <button type="submit" class="btn d-block w-100 btn-primary mt-3">Accedi</button>
                            </form>
                            <div class="text-center my-3">oppure</div>
                            <div class="mt-3 sso-button-container text-center">
                                <GoogleLogin
                                    :clientId="googleClientId"
                                    :callback="handleGoogleCallback"></GoogleLogin>
                            </div>
                            <div v-if="errorMessage?.text" class="alert alert-danger mt-2">{{ errorMessage.text }}</div>
                        </div>
                        <div v-if="step == 2">

                            <form @submit.prevent="saveCustomFields">
                                <div v-for="(cf,i) in customSignupFields" :key="i">
                                    <div v-if="cf.data.type == 'checkbox'" class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" :id="'cf-'+cf.fieldID" v-model="customSignupFieldsResponses[cf.fieldID]">
                                        <label class="form-check-label" :for="'cf-'+cf.fieldID">{{ cf.data.text }}</label>
                                    </div>
                                </div>
                                <small>Registrandoti a <b>{{ ps.getTitle() }}</b> accetti i <a href="#" target="_blank">Termini di servizio</a>, le <a href="#" target="_blank">Condizioni d'Uso</a> e dai il tuo consenso al trattamento dei dati personali forniti per le finalità indicate nella nostra <a href="#" target="_blank">Privacy Policy</a>.</small>
                                <button type="submit" class="btn d-block w-100 btn-primary mt-3">Accedi</button>
                            </form>
                        </div>
<div>
    
                            <hr>
    
                            <div class="recover-container">
                                <router-link to="/recupero-password">Ho dimenticato la password</router-link>
                            </div>
    
    
                            <div class="mt-3 signup-container">
                                <span>Non hai un account?</span><br>
                                <router-link to="/registrati">Registrati</router-link>
                            </div>
</div>
                        
                

                    </div>
                </div>
    </div>
</template>
<style lang="scss" scoped>


.container-fluid {
    padding: 1rem;
    min-height: 100vh;
}



.helper-sso {
    font-size: .8rem;
    color: var(--bs-text-body-tertiary);
}

.login-card {

    display: block;


    .banner-left {
        user-select: none;

        .logo {
            text-align: center;
            img {
                max-height: 3rem;
            }
        }

        .banner-left-content {
            padding: 1rem;

        }

        h2 {
            margin: 1rem 0;
            font-size: 20px;
            color: white;
            text-align: center;
        }

        .banner-image {
            display: none;
        }
    }

    .login-area {
        background: white;
        padding: 3rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;

        h1 {
            font-size: 20px;
        }

        .sso-button-container {
            iframe {
                width: 100%;
            }
        }

        .alert {
            border-radius: 0;
            padding: .8rem;
            text-align: center;
            font-size: 14px;
        }

        .recover-container {
            text-align: center;
            a {
                color: var(--bs-body-color);
            }
        }

        .signup-container {
            text-align: center;
            a {
                font-size: 17px;
                color: var(--bs-body-color);
                text-decoration: underline;
                text-transform: uppercase;
                font-weight: bold;
            }
        }
    }

    .form-group {
        margin: 1rem 0;
    }

    .card-footer {
        a {
            margin-right: 0.5rem;
        }
    }
}

@media (min-width: 992px) {

.container-fluid {
    display: grid;
    place-items: center;
    min-height: 100vh;  
}



.helper-sso {
    font-size: .8rem;
    color: var(--bs-text-body-tertiary);
}

.login-card {

    width: 100%;
    max-width: 900px;
    min-width: 300px;
    margin: 0 auto;
    display: flex;
    border-radius: .7rem!important;
    box-shadow: 0 0 15px rgba(0,0,0,.25);
    overflow: hidden;


    .banner-left {
        flex: 1;
        user-select: none;

        .banner-left-content {
            padding: 3rem;
        }

        .logo {

            padding: 1rem;
            text-align: center;

            img {
                max-height: 3rem;
            }
        }

        h2 {
            margin: 1rem 0;
            font-size: 40px;
            color: white;
            text-align: center;
        }

        .banner-image {
            width: 80%;
            display: block;
            margin: 0 auto;
        }
    }

    .login-area {
        background: white;
        padding: 3rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;

        h1 {
            font-size: 20px;
        }

        .sso-button-container {
            iframe {
                width: 100%;
            }
        }

        .alert {
            border-radius: 0;
            padding: .8rem;
            text-align: center;
            font-size: 14px;
        }

        .recover-container {
            text-align: center;
            a {
                color: var(--bs-body-color);
            }
        }

        .signup-container {
            text-align: center;
            a {
                font-size: 17px;
                color: var(--bs-body-color);
                text-decoration: underline;
                text-transform: uppercase;
                font-weight: bold;
            }
        }
    }

    .form-group {
        margin: 1rem 0;
    }

    .card-footer {
        a {
            margin-right: 0.5rem;
        }
    }
}

}

</style>