<script setup>
import { useProjectStore } from "../stores/project"
const getAppVersion = () => {
    return import.meta.env.VITE_APP_VERSION
}

const ps = useProjectStore()
const theme = ps.getTheme()
</script>
<template>
    <div class="footer-outer">
        <footer class="container">
            <div class="achab logo">
                <img src="@/assets/achab.svg" alt="LOGO ACHAB">
                <div>
                Via L. Pasteur, 3 - 10146 Torino (TO)<br>
                info@achabgroup.it<br>
                P.IVA e C.F 02063190413
            </div>
            </div>
        
            <div class="bcorp logo">
                <img style="height: 4rem" src="@/assets/certificazioni.png" alt="B Corp">
                <div>
                    Sistema di Gestione per la Qualità e Ambiente<br>
                    ISO 9001:2015 e 14001:2015<br>
                    certificato da KIWA CERMET ITALIA
                </div>
            </div>
            <div class="links">
                <a href="#">Informativa Privacy</a>
                <a href="#">Informativa Cookies</a>
                <div v-if="theme.footer.showSocialIcons" class="socials">
                    <a href="https://www.facebook.com/achabgroup/" target="_blank" class="circle facebook"></a>
                    <a href="https://www.linkedin.com/company/achab-srl/" target="_blank" class="circle linkedin"></a>
                    <a href="https://www.instagram.com/achabgroup/" target="_blank" class="circle instagram"></a>
                    <a href="https://www.youtube.com/@achabsrlsocietabenefit-ach1665" target="_blank" class="circle youtube"></a>
                </div>

                <div class="text-center mt-2"><small>v{{ getAppVersion() }}</small></div>
            </div>
        </footer>
    </div>
</template>
<style lang="scss" scoped>

@media screen and (max-width: 768px) {
    footer {
        flex-direction: column;
        gap: 10px;
    }

    footer > div {
        flex-direction: column;
        text-align: center
    }
}

.footer-outer {
    background-color: var(--bs-body-color);

}

footer {

    display: flex;
    justify-content: space-between;
    align-items: center;

    color: var(--bs-body-bg);
    font-size: 13px;

    padding: 2rem 1rem;
    margin-top: 2rem;

    .logo {

        display: flex;
        align-items: center;
        gap: 20px;

        min-height: 2rem;
        margin-left: 2rem;

        img {
            height: 2rem;
        }
    }

    .links {

        display: flex;
        flex-direction: column;

        a {
            color: var(--bs-body-bg);
        }

        .socials {

            margin-top: 10px;

            $size: 25px;

            .circle {
                border: 1px solid white;
                border-radius: calc($size/2);
                height: $size;
                width: $size;
                margin-right: calc($size/3);
                display: inline-block;
                background-size: auto 65%;
                background-position: center center;
                background-repeat: no-repeat;

                &.facebook {
                    background-image: url('@/assets/social_icons/facebook.svg');
                }


                &.linkedin {
                    background-image: url('@/assets/social_icons/linkedin.svg');
                }

                &.instagram {
                    background-image: url('@/assets/social_icons/instagram.svg');
                }

                &.youtube {
                    background-image: url('@/assets/social_icons/youtube.svg');
                }
            }

            
        }

    }
}
</style>