<script setup>
import { setToken } from '../../utils/auth';
import { request } from '../../utils/request';
import { reactive, inject } from 'vue';
import { useRouter } from 'vue-router';
import { GoogleLogin } from 'vue3-google-login'
import { useProjectStore } from '@/stores/project';
import { useTerraParkStore } from '@/stores/commons';
import useToasts from '@/stores/toasts';

let email = "", password = "";

const errorMessage = reactive({ text: null });
const ps = useProjectStore()
const tps = useTerraParkStore()

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
                title : "Benvenuto!",
                content : `Login avvenuto correttamente`
            })

            const after_login_redirect = sessionStorage.getItem("sspa_after_login_redirect");
            if (after_login_redirect !== null) {
                sessionStorage.removeItem("sspa_after_login_redirect");
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

const handleGoogleCallback = (res) => {

    request({
        task: "core.public.googleLogin",
        data: {
            token: res.credential,
        },
        callback: function (data) {
            setToken(data.jwt);
            //setUserInfo(data.userinfo);

            // toasts.addToast({
            //     title : `<i class="bi bi-emoji-smile"></i> Accesso effettuato`,
            // })

            const after_login_redirect = sessionStorage.getItem("sspa_after_login_redirect");
            if (after_login_redirect !== null) {
                router.push({ path: after_login_redirect })
            } else {
                router.push({ path: "/" })
            }
            e.submitter.disabled = false;
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

                        <div class="logo"><img :src="customTheme.logo.image_url"></div>

                        <h2 v-html="customTheme.loginPage.welcomeText"></h2>

                        <img class="banner-image" src="@/assets/graficalogin.jpg">
                    </div>
                    <div class="login-area">

                        <div>
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
                                    clientId="265242670424-qp64siqcjtuku5v6uvvmabsq4omvtk1t.apps.googleusercontent.com"
                                    :callback="handleGoogleCallback"></GoogleLogin>
                            </div>
                            <div v-if="errorMessage?.text" class="alert alert-danger mt-2">{{ errorMessage.text }}</div>
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
        padding: 1rem;
        user-select: none;

        .logo {
            text-align: center;
            img {
                max-height: 3rem;
            }
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
        padding: 3rem;
        user-select: none;

        .logo {
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