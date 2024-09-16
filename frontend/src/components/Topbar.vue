<script setup>
import LanguageSwitcher from "./LanguageSwitcher.vue"
import {ref} from 'vue'
import { logout, isLogged } from "@/utils/auth";
import { request } from "@/utils/request";
import { useProjectStore } from "@/stores/project";
import { useTerraParkStore } from "@/stores/commons";

const iniziali = ref('')
const ps = useProjectStore()
const theme = ps.getTheme()

const tps = useTerraParkStore()
const temi_visibili = tps.getThemes()

if (isLogged()) {
    request({
        task : "profile/get",
        callback : function(dt) {
            iniziali.value = dt.nome.substring(0,1).toUpperCase() +  dt.cognome.substring(0,1).toUpperCase() 
        }
    })
}


</script>
<template>
    <div>
        <nav class="navbar first-nav bg-body-tertiary">
            <div class="container-fluid">
                <div class="logos">
                    <a href="/">
                        <img class="logo" :src="theme.logo.image_url">
                    </a>
                </div>
                <div class="right">
                    <input type="text" disabled class="form-control global-search" :placeholder="$t('nav.cerca')">
                    <LanguageSwitcher />
                    <RouterLink class="icon-action" to="/preferiti/contenuti"><span class="material-symbols-outlined">favorite</span></RouterLink>
                    <RouterLink class="icon-action" to="/attestati"><span class="material-symbols-outlined">beenhere</span></RouterLink>
                    <div class="btn-group no-arrow">
                        <button class="icon-action dropdown-toggle" data-bs-toggle="dropdown"><span class="material-symbols-outlined">notifications</span></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">{{ $t('nav.notifiche') }}</h6></li>
                            <p class="px-3">Non hai notifiche</p>
                        </ul>
                    </div>
        
                    <div class="btn-group profile-dropdown no-arrow">
                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                           {{ iniziali }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text"></span></li>
                            <!-- <li><button class="dropdown-item" type="button">Action</button></li>
                            <li><button class="dropdown-item" type="button">Another action</button></li> -->
                            <li><h6 class="dropdown-header">Preferiti</h6></li>
                            <li><RouterLink to="/preferiti/corsi" class="dropdown-item">Corsi</RouterLink></li>
                            <li><RouterLink to="/preferiti/contenuti" class="dropdown-item">Contenuti</RouterLink></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><h6 class="dropdown-header">Profilo</h6></li>
                            <li><RouterLink class="dropdown-item" to="/profilo">Dati personali</RouterLink></li>
                            <li><RouterLink class="dropdown-item" to="/attestati">Qualifiche e attestati</RouterLink></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><RouterLink to="/cronologia" class="dropdown-item">Cronologia</RouterLink></li>
                            <li><RouterLink to="/contatti" class="dropdown-item">Contatti e assistenza</RouterLink></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><button @click="logout" class="dropdown-item" type="button">Logout</button></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <nav class="navbar second-nav bg-body-tertiary">
            <div class="container-fluid pages">
                <router-link to="/">Home</router-link>
                <router-link v-if="temi_visibili.length > 1" to="/temi">{{$t('nav.temi')}}</router-link>
                <router-link v-else to="/tema">Tema</router-link>
                <router-link to="/catalogo">{{$t('nav.catalogo')}}</router-link>
                <router-link to="/autori">{{$t('nav.autori')}}</router-link>
            </div>
        </nav>
    </div>
</template>
<style lang="scss" scoped>

    .first-nav {
        padding: 3px;
        border-bottom: 1px solid var(--bs-border-color);
    

        .logos {
            display: flex;
            overflow: hidden;

            .logo-link {
                text-decoration: none;
            }

            .logo {
                display: block;
                max-height: 60px;
                margin-right: .5rem;
            }
        }

        .right {
            padding: 11px 0;
            display: flex;
            gap: .5rem;
            align-items: center;

            .global-search {
                border-radius: 2rem!important;
            }

            .icon-action {
                text-decoration: none;
                background: none;
                border: 0;
                color: var(--bs-body-color);
                padding: 0;
                margin: 0;

                span {
                    display: block;
                }
            }

            
            .dropdown-toggle {

                border-radius: 1rem;
                width: 2rem;
                height: 2rem;
                margin: 0;
                padding: 0;

                &.show {
                    background: rgba(var(--bs-body-color-rgb), .5);
                    color: var(--bs-body-bg);
                }

                


            }

            
            .dropdown-menu {
                margin-top: 3px;
                transform: translateX(12px);
                border-top: 3px solid var(--bs-body-color);
                border-radius: 0;
            }
            

            .no-arrow {
                .dropdown-toggle:after {
                    display: none;
                }
            }

            .profile-dropdown {

                .dropdown-toggle {
                    border: 1px solid rgba(var(--bs-body-color-rgb), .5);
                    border-radius: 50px!important;
                }

               
            }

        }

    }
    
    .second-nav {
        border-bottom: 3px solid var(--bs-body-color);

        .container-fluid {
            justify-content: center;
            gap: 3rem;

            a {
                text-transform: uppercase;
                color: rgba(var(--bs-body-color-rgb), .6);
                text-decoration: none;
                font-weight: bold;

                &.router-link-active, &:hover {
                    color: rgba(var(--bs-body-color-rgb), 1);
                }
            }
        }

    }

   

</style>