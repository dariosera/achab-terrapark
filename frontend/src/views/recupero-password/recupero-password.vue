<script setup>
import { request } from '../../utils/request';
import { reactive, inject } from 'vue';
import { useRouter } from 'vue-router';
import { useProjectStore } from '@/stores/project';
import { useTerraParkStore } from '@/stores/commons';
import { setToken } from '@/utils/auth';
import useToasts from '@/stores/toasts';


const resetPassword = reactive({
    email: '', 
})

const errorMessage = reactive({ text: null });
const ps = useProjectStore()
const tps = useTerraParkStore()

const customTheme = reactive(ps.getTheme())

const features = reactive({});

const router = useRouter();

function submit(e) {
    request({
        task : "core/public/recuperoPassword",
        data : resetPassword,
        callback : function(dt) {
            
            useToasts().addToast({
                title : "Richiesta inviata",
                content : `Controlla la posta elettronica.`
            })

        },
        error: function (err) {
            e.submitter.disabled = false;
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

                        <div>
                            <h1>Recupera la password</h1>
                            <form @submit.prevent="submit">

                                <div class="form-group">
                                    <label autocomplete="email" for="email">Email</label>
                                    <input required v-model="resetPassword.email" type="email" class="form-control" id="username"
                                       >
                                </div>
                                
                                <!-- <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="persistent">
                                    <label class="form-check-label" for="persistent">Resta collegato</label>
                                </div> -->
                                <button type="submit" class="btn d-block w-100 btn-primary mt-3">Recupera la password</button>
                            </form>
                            
                            <div v-if="errorMessage?.text" class="alert alert-danger mt-2" v-html="errorMessage.text"></div>
                        </div>
<div>
    
                            <hr>
    
                          
    
                            <div class="mt-3 signup-container">
                                <span>Non sei registrato?</span><br>
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