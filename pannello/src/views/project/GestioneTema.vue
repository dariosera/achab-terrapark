<script setup>
import {defineProps, ref, reactive} from 'vue'
import { request, upload } from 'kadro-core';

const props = defineProps(["project"])

const theme = reactive({
    logo : {
        image : null,
        image_url : null
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


</script>
<template>
    {{ theme }}
    <div class="card">
        <div class="card-body">
            
            <div>
                <label>Logo</label>

                    <form @submit.prevent="doUpload" class="d-flex my-2" v-if="!theme?.logo?.image_url">
                        <input required type="file" class="form-control form-control-sm me-2" accept="image/png">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i></button>
                    </form>
                    <div v-else>
                        <img :src="theme.logo.image_url" class="">
                        <button class="btn btn-sm btn-link" @click="() => theme.logo.image_url = null">Cambia immagine</button>
                    </div>
            </div>
            
        </div>
    </div>
</template>