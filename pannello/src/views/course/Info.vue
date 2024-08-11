<script setup>
import {SearchSelect, request, upload, Modal} from 'kadro-core';
import {reactive, defineProps} from 'vue';

const emit = defineEmits(["deleted"])


const props = defineProps(["content"]);

const newTag = reactive({
    add : function() {
       request({
        task : "content/addTag",
        data : {
            contentID : props.content.contentID,
            tagID : newTag.id,
        },
        callback : function(dt) {
            newTag.id = undefined;
            props.content.tags = dt;
        }
       })
    },
    id : undefined,
})

const doUpload = function(e) {

console.log(e)
let formData = new FormData();
formData.append("file", e.target[0].files[0]);
formData.append("permalink",props.content.permalink)

upload({
    task : "content/uploadImg",
    data : formData,
    callback : function(dt) {
        props.content.image = dt.file_id;
        props.content.image_url = dt.s3.url;
    }
})

}

const deleteTag = (id) => {
    request({
        task : "content/removeTag",
        data : {
            contentID : props.content.contentID,
            tagID : id,
        },
        callback : function(dt) {
            props.content.tags = dt;
        }
       })
}

const confermaEliminazione = reactive({
    actions : [
        {
            text : 'Confermo, elimina il contenuto',
            class : 'btn-danger',
            callback : function() {
                request({
                    task : "content/softDelete",
                    data : {
                        permalink : props.content.permalink
                    },
                    callback : function() {
                        emit('deleted')
                    }

                })
            }
        }
    ],
    modal : null,
})

</script>
<template>
    <div class="row" v-if="props.content">
        <div class="col-12">
            <div class="row g-3">
                <div class="col-lg-12">
                    <label>Titolo</label>
                    <input type="text" class="form-control" v-model="props.content.title">
                </div>
                
                <div class="col-lg-6">
                    <label>Tema</label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="themes.search"
                        onCreate="themes.create" v-model="props.content.themeID" />
                </div>
                <div class="col-lg-6">
                    <label>Argomento</label>
                    <SearchSelect :disabled="!content?.themeID" class="w-100 d-block" required placeholder="Cerca..."
                        task="topics.search" onCreate="topics.create" v-model="props.content.topicID"
                        :data="{themeID: props.content.themeID}" />
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label>Descrizione</label>
                        <textarea rows="5" class="form-control w-100" v-model="props.content.description"></textarea>
                        <small v-if="props.content.description">
                            {{ props.content.description.split(" | ")[0].length }} / 130 - Descr. breve | {{
                            props.content.description.replace(" | ","").length }} / 400 - Descr. lunga
                        </small>
                    </div>

                    <div class="form-group mt-3">
                        <label>Tag</label>

                        <div class="tag-area mt-2">
                            <span v-for="tag,i in props.content.tags" :key="i" class="badge border text-muted px-2 me-2">
                                {{ tag.description }}
                                <i style="cursor: pointer" @click="deleteTag(tag.tagID)" class="bi bi-x-lg ms-2"></i></span>

                            <small v-if="props.content.tags.length === 0">Nessun tag</small>
                        </div>
                        <form @submit.prevent="newTag.add" class="d-flex mt-3">
                            <SearchSelect class="me-1" required placeholder="Aggiungi tag" task="tags.search"
                                onCreate="tags.create" v-model="newTag.id" />
                            <button class="btn btn-primary" type="submit"><i class="bi bi-plus"></i> Aggiungi</button>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div>
                        <label>Lingua</label>
                        <SearchSelect class="w-100" required placeholder="Cerca..." task="languages.search"
                            v-model="props.content.language" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="">
                        <label>Cliente</label>
                        <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="customers.search"
                            v-model="props.content.customerID" />
                    </div>
                </div>
                


            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="card" >
                <div class="card-body">
                    <label>Immagine di anteprima</label>

                    <form @submit.prevent="doUpload" class="d-flex my-2" v-if="props.content.image_url==null">
                        <input required type="file" class="form-control form-control-sm me-2" accept="image/jpeg">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i></button>
                    </form>
                    <div v-else>
                        <img :src="props.content.image_url" class="w-100">
                        <button class="btn btn-sm btn-link" @click="() => props.content.image_url = null">Cambia immagine</button>
                    </div>
                </div>
            </div>


        </div>
    </div>


    <Modal title="Conferma eliminazione" :actions="confermaEliminazione.actions" :canDismiss="true"
        size="sm" @ready="(t) => confermaEliminazione.modal = t">
        <p>Confermi di voler eliminare il contenuto?</p>
    </Modal>
</template>