<script setup>
import {defineProps, ref, reactive} from 'vue'
import { request, upload } from 'kadro-core';

const props = defineProps(["project"])

const theme = reactive({
    logo : {
        image : null,
        image_url : null
    },
    loginPage : {
        background: null,
        cardBackground : null,
        welcomeText : null,
    },
    footer : {
        showSocialIcons : true,
    },
    ...props.project.theme
})

const doUpload = function (e) {

    console.log(e)
    let formData = new FormData();
    formData.append("file", e.target[0].files[0]);
    formData.append("projectID", props.project.projectID)

    upload({
        task: "project/uploadLogo",
        data: formData,
        callback: function (dt) {

            if (!theme.logo) {
                theme.logo = {}
            }

            theme.logo.image = dt.file_id;
            theme.logo.image_url = dt.s3.url;

            request({
                task : "project/updateTheme",
                data : {
                    projectID : props.project.projectID,
                    theme,
                },
                callback : (dt) => {
                    //ok
                }
            })
        }
    })

}

function saveTheme() {
    request({
        task : "project/updateTheme",
        data : {
            projectID : props.project.projectID,
            theme,
        },
        callback : (dt) => {
            //ok
        }
    })
}


</script>
<template>
    <div class="card">
        <div class="card-body">
            
            <div>
                <label>Logo</label>

                    <form @submit.prevent="doUpload" class="d-flex my-2" v-if="!theme?.logo?.image_url">
                        <input required type="file" class="form-control form-control-sm me-2" accept="image/png">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i></button>
                    </form>
                    <div v-else>
                        <img :src="theme.logo.image_url+'?t='+(new Date()).getTime()" class="">
                        <button class="btn btn-sm btn-link" @click="() => theme.logo.image_url = null">Cambia immagine</button>
                    </div>
            </div>

            <hr>
        
            <table class="table table-sm">
                <tr>
                    <th colspan="3">Pagina di login</th>
                </tr>
                <tr>
                    <td>Colore di sfondo</td>
                    <td><input class="form-control" type="text" v-model="theme.loginPage.background"></td>
                    <td><div :style="{'backgroundColor': (theme.loginPage.background), width: '6rem'}">&nbsp;</div></td>
                </tr>
                <tr>
                    <td>Sfondo card</td>
                    <td><input class="form-control" type="text" v-model="theme.loginPage.cardBackground"></td>
                    <td><div :style="{'backgroundColor': (theme.loginPage.cardBackground), width: '6rem'}">&nbsp;</div></td>
                </tr>
                <tr>
                    <td>Testo benvenuto</td>
                    <td colspan="2"><input class="form-control" type="text" v-model="theme.loginPage.welcomeText"></td>
                </tr>
                <tr>
                    <th colspan="3">Footer</th>
                </tr>
                <tr>
                    <td>Mostra social</td>
                    <td colspan="2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" v-model="theme.footer.showSocialIcons" type="checkbox" role="switch" id="mostraSocial">
                            <label class="form-check-label" for="mostraSocial">{{ theme.footer?.showSocialIcons == true ? 'Social visibili' : 'Social nascosti'}}</label>  
                        </div>

                    </td>
                </tr>
            </table>

        </div>
        <div class="card-footer text-end">
            <button class="btn btn-primary" @click="saveTheme"><i class="bi bi-save"></i> Salva</button>
        </div>
    </div>
</template>