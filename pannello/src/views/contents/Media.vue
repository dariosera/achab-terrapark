<script setup>
import {watch, reactive} from 'vue';
import {upload, request} from 'kadro-core';
const props = defineProps(["content"]);

watch(() => props.content.media.mediaType, (prev,next) => {
    if ("undefined" === typeof(props.content.media[prev+"_data"])) {
        props.content.media[prev+"_data"] = {}
    }
})

const uploadPdf = function(e) {

    console.log(e)
    let formData = new FormData();
    formData.append("file", e.target[0].files[0]);
    formData.append("permalink",props.content.permalink)


    upload({
        task : "content/uploadPdf",
        data : formData,
        callback : function(dt) {
            props.content.media.pdf_data.file_id = dt.file_id;
            props.content.previews.pdf = dt.s3.url;
        }
    })

}

const uploadAudio = function(e) {

console.log(e)
let formData = new FormData();
formData.append("file", e.target[0].files[0]);
formData.append("permalink",props.content.permalink)


upload({
    task : "content/uploadAudio",
    data : formData,
    callback : function(dt) {
        props.content.media.audio_data.file_id = dt.file_id;
        props.content.previews.audio = dt.s3.url;

        alert()
    }
})

}

const showPdf = () => {
    request({
        task : "core.s3.get",
        data : {
            key : `documents/${props.content.permalink}.pdf`,
            duration: 1,
        },
        callback : function(dt) {
            var newWin = window.open(dt.url, '_blank');            
            if(!newWin || newWin.closed || typeof newWin.closed=='undefined') { 
                alert("Popup bloccato")
            }
            
        }
    })
}

const audioPlayerReady = (ev) => {
    const duration = parseInt(ev.target.duration);

    if (duration !== props.content.meta?.duration) {
        request({
            task : "content/updateDuration",
            data : {
                permalink : props.content.permalink,
                duration,
            },
            callback : (dt) => {
                props.content.meta = dt;
            }
        })
    }
}

</script>
<template>
    <div class="row">
        <div class="col-12 form-mediatype">
            <label class="me-2">Tipo di media</label>
            <select v-model="props.content.media.mediaType" class="form-select" >
                <option value="vimeo">Vimeo</option>
                <option value="pdf">Pdf</option>
                <option value="audio">Audio</option>
                <option value="h5p">H5P</option>
                <option value="embed">Embed</option>
            </select>
        </div>
        <div class="col-12"><hr></div>

        <div v-if="props.content.media.mediaType == 'vimeo'" class="col-12">
            <div class="form-group">
                <label>Vimeo ID</label>
                <input type="text" class="form-control" v-model="props.content.media.vimeo_data.video_id">
            </div>
        </div>

        <div v-if="props.content.media.mediaType == 'pdf'" class="col-12">
            <div class="form-group">
                <label><i class="bi bi-file-earmark-pdf"></i>  Carica pdf</label>
                
                <form @submit.prevent="uploadPdf" class="d-flex my-2">
                    <input required type="file" class="form-control form-control-sm me-2" accept="application/pdf">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i></button>
                </form>

                <a v-if="props.content?.previews.pdf" :href="props.content?.previews.pdf" target="_blank" class="d-block w-100 mt-2 btn btn-sm btn-outline-primary" ><i class="bi bi-box-arrow-up-right"></i> Visualizza pdf attuale</a>

            </div>
        </div>

        <div v-if="props.content.media.mediaType == 'audio'" class="col-12">
            <div class="form-group">
                <label><i class="bi bi-filetype-mp3"></i> Carica mp3</label>
                
                <form @submit.prevent="uploadAudio" class="d-flex my-2">
                    <input required type="file" class="form-control form-control-sm me-2" accept="audio/mpeg">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i></button>
                </form>

                <audio v-if="props.content.previews.audio" :src="props.content.previews.audio" controls @canplay="audioPlayerReady"></audio>

            </div>
        </div>

        <div v-if="props.content.media.mediaType == 'h5p'" class="col-12">
            <div class="form-group">
                <label>Video ID</label>
                <input type="text" class="form-control" v-model="props.content.media.h5p_data.video_id">
            </div>
        </div>

        <div v-if="props.content.media.mediaType == 'embed'" class="col-12">
            <div class="form-group">
                <label>URL</label>
                <input type="text" class="form-control" v-model="props.content.media.embed_data.url">
            </div>
        </div>

    </div>
</template>
<style lang="scss" scoped>
.form-mediatype {
    display: flex;
    align-items: center;

    select {
        flex: 1;
    }
}
</style>