<script setup>
import {ref} from 'vue'
import {request, upload} from 'kadro-core'

const props = defineProps(["course"]);
const certBlob = ref(null);

const uploadPdf = function(e) {

console.log(e)
let formData = new FormData();
formData.append("file", e.target[0].files[0]);
formData.append("course",props.course)



upload({
    task : "course/uploadCertificateTemplate",
    data : formData,
    callback : function(dt) {
        getCertificate()
    }
})

}

function getCertificate() {
    request({
    task : 'course/getCertificateTemplate',
    data : {
        course : props.course,
    },
    callback : (dt) => {
        if (dt.length === 0) {

        } else {
            certBlob.value = dt.url;
        }
    }
})
}
getCertificate()

</script>
<template>
    <div class="card p-3 mt-3">

        <div>
            <div class="form-group">
                <label><i class="bi bi-file-image"></i>  Template certificato di fine corso</label>
                
                <form @submit.prevent="uploadPdf" class="d-flex my-2">
                    <input required type="file" class="form-control form-control-sm me-2" accept="image/jpeg">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i></button>
                </form>

                <a v-if="certBlob" :href="certBlob" target="_blank" class="d-block w-100 mt-2 btn btn-sm btn-outline-primary" ><i class="bi bi-box-arrow-up-right"></i> Visualizza modello attuale</a>

            </div>
        </div>
    </div>
</template>