<script setup>
import {watch, reactive} from 'vue';
import {upload, request} from 'kadro-core';
const props = defineProps(["content"]);
import Papa from 'papaparse'
import useModals from '../../../../frontend/src/stores/modals';


function handleCsvUpload(event) {
    const file = event.target.files[0];
    if (file) {
        parseCSV(file);
    }
};

function parseCSV(file) {
    Papa.parse(file, {
        header: false,
        complete: (results) => {

            // Rimuovo ultima riga se vuota
            if ("undefined" === typeof(results.data[results.data.length-1][0])) {
                results.data = results.data.slice(0,results.data.length -1);
            }

            props.content.media.quiz_data = {
                questions: [],
                duration: null,
                threshold: null,
            }
           

            results.data.forEach((row, i) => {
                if (i == 0) {
                    // Riga intestazione
                    return; 
                }
                
                const q = {
                    "question" : row[0],
                    "answers" : [],
                    "info" : row[1],
                }

                for (let k=2; k<row.length; k++) {
                    q.answers.push(row[k])
                }

                props.content.media.quiz_data.questions.push(q);

            })

            

        },
        error: (error) => {
            console.error("Error parsing CSV:", error);
        }
    });
}

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

const uploadCsv = function(e) {

console.log(e)
let formData = new FormData();
formData.append("file", e.target[0].files[0]);
formData.append("permalink",props.content.permalink)


upload({
    task : "content/uploadQuizCsv",
    data : formData,
    callback : function(dt) {
        props.content.media.quiz_data = dt.data;
    }
})

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
                <option value="quiz">Quiz</option>
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

        <div v-if="props.content.media.mediaType == 'quiz'" class="col-12">
            <div class="form-group">
                <label><i class="bi bi-filetype-csv"></i> Carica csv</label>

                 <input @change="handleCsvUpload" required type="file" class="form-control form-control-sm me-2" accept="text/csv">

                 <div class="d-flex mt-2 align-items-center">
                    <span class="me-2">Durata:</span>
                    <input v-model="props.content.media.quiz_data.duration" disabled type="number" class="form-control">
                 </div>
                 <small>Durata in minuti, facoltativa. Lasciando vuoto il campo, il test non ha limiti di tempo.</small>

                 <div class="d-flex mt-2 align-items-center">
                    <span class="me-2">Punteggio minimo:</span>
                    <input v-model="props.content.media.quiz_data.threshold" type="number" class="form-control">
                 </div>
                 <small>Punteggio minimo richiesto per superare il test. Si può lasciare vuoto.</small>
                 
                 <hr>

                 <div v-if="props.content.media.quiz_data.questions">Il file csv contiene {{ props.content.media.quiz_data.questions.length }} domande.

                 </div>
              
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