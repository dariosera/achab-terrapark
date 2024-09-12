<script setup>

import { defineProps, defineEmits, reactive, watch } from 'vue';
import { request, Modal, upload, SearchSelect } from 'kadro-core';

const props = defineProps(["slide"])
const emit = defineEmits(["created", "modified"])

const actions = [
{
        text : "Esci senza salvare",
        class : "btn-outline-secondary",
        callback : function() {

            if (props.slide.isNew) {
                    request({
                        task: "homepage/deleteSlide",
                        data : {
                            slideID : props.slide.slideID
                        },
                        callback : function(dt) {

                        }
                    })
            }
           
        }
    },
    {
        text : "Salva",
        class : "btn-primary",
        callback : function() {

            request({
                task: "homepage/editSlide",
                data : {
                    ...props.slide
                },
                callback : function(dt) {
                    if (props.slide.isNew) {
                        props.slide.isNew = false;
                        emit('created', props.slide.slideID);
                    } else {
                        emit('modified');
                    }
                }
            })
        }
    }
]


const doUpload = function(e) {

    console.log(e)
    let formData = new FormData();
    formData.append("file", e.target[0].files[0]);
    formData.append("slideID",props.slide.slideID)

    upload({
        task : "homepage/uploadSlideImg",
        data : formData,
        callback : function(dt) {
            props.slide.image = dt.file_id;
            props.slide.image_url = dt.s3.url;
        }
    })

}

</script>
<template>
    <Modal :title="props.slide?.isNew ? 'Nuova slide' : 'Modifica slide'" :actions="actions"
        :canDismiss="false" size="xl" @ready="(t) => { emit('ready', t)}">
        
        <div v-if="props.slide?.slideID" class="row g-2" >

    
            <div class="col-lg-7">
                <div class="form-group">
                    <label>Titolo</label>
                    <input type="text" class="form-control" v-model="props.slide.title">
                </div>
                <div class="form-group">
                    <label>Contenuto</label>
                    <textarea class="form-control" v-model="props.slide.content"></textarea>
                </div>
                <div class="form-group">
                    <label>Testo link</label>
                    <input type="text" class="form-control" v-model="props.slide.link_text">
                </div>
                <div class="form-group">
                    <label>Indirizzo link</label>
                    <input type="text" class="form-control" v-model="props.slide.link_href">
                </div>
                <div class="form-group">
                    <label>Cliente</label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="customers.search"
                        v-model="props.slide.customerID" />
                </div>
            </div>
            <div class="col-lg-5">
                <label>Immagine</label>

                <form @submit.prevent="doUpload" class="d-flex my-2" v-if="props.slide.image_url==null">
                    <input required type="file" class="form-control form-control-sm me-2" accept="image/jpeg">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i></button>
                </form>
                <div v-else>
                    <img :src="props.slide.image_url+'?t='+(new Date()).getTime()" style="display:block; max-height: 200px; max-width: 100%">
                    <button class="btn btn-sm btn-link" @click="() => props.slide.image_url=null">Cambia immagine</button>
                </div>
            </div>

            
        </div>
    </Modal>
</template>