<script setup>
import {defineProps, ref, reactive} from 'vue'
import { request, upload } from 'kadro-core';

const props = defineProps(["project"])

const privacy = reactive({
    ...props.project.privacy
})

// const doUpload = function (e) {

//     console.log(e)
//     let formData = new FormData();
//     formData.append("file", e.target[0].files[0]);
//     formData.append("projectID", props.project.projectID)

//     upload({
//         task: "project/uploadLogo",
//         data: formData,
//         callback: function (dt) {

//             if (!theme.logo) {
//                 theme.logo = {}
//             }

//             theme.logo.image = dt.file_id;
//             theme.logo.image_url = dt.s3.url;

//             request({
//                 task : "project/updateTheme",
//                 data : {
//                     projectID : props.project.projectID,
//                     theme,
//                 },
//                 callback : (dt) => {
//                     //ok
//                 }
//             })
//         }
//     })

// }

// const doUploadExtra = function (e) {

// console.log(e)
// let formData = new FormData();
// formData.append("file", e.target[0].files[0]);
// formData.append("projectID", props.project.projectID)

// upload({
//     task: "project/uploadExtraLogos",
//     data: formData,
//     callback: function (dt) {

//         if (!theme.extraLogos) {
//             theme.extraLogos = {}
//         }

//         theme.extraLogos.image = dt.file_id;
//         theme.extraLogos.image_url = dt.s3.url;

//         request({
//             task : "project/updateTheme",
//             data : {
//                 projectID : props.project.projectID,
//                 theme,
//             },
//             callback : (dt) => {
//                 //ok
//             }
//         })
//     }
// })

// }

function savePrivacy() {
    request({
        task : "project/updatePrivacy",
        data : {
            projectID : props.project.projectID,
            privacy,
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
            
        
            <table class="table table-sm">
                <!-- <tr>
                    <th colspan="3">Pagina di login</th>
                </tr> -->
                <tr>
                    <td>Termini e condizioni</td>
                    <td colspan="2"><input class="form-control" type="text" v-model="privacy.termsAndConditions"></td>
                </tr>
                <tr>
                    <th colspan="3">Iubenda</th>
                </tr>
                <tr>
                    <td>siteID</td>
                    <td colspan="2"><input class="form-control" type="text" v-model="privacy.iubendaSiteId"></td>
                </tr>
                <tr>
                    <td>cookiePolicyId</td>
                    <td colspan="2"><input class="form-control" type="text" v-model="privacy.iubendaCookiePolicyId"></td>
                </tr>
            </table>

        </div>
        <div class="card-footer text-end">
            <button class="btn btn-primary" @click="savePrivacy"><i class="bi bi-save"></i> Salva</button>
        </div>
    </div>
</template>