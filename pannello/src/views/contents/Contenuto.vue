<script setup>
import {SearchSelect, request, upload, useModals} from 'kadro-core';
import {reactive, defineProps} from 'vue';
import Media from './Media.vue';

const emit = defineEmits(["deleted"])

const um = useModals()

const props = defineProps(["content","noStandalone"]);

const newTag = reactive({
    add : function() {
       request({
        task : "content/addTag",
        data : {
            permalink : props.content.permalink,
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
            permalink : props.content.permalink,
            tagID : id,
        },
        callback : function(dt) {
            props.content.tags = dt;
        }
       })
}



function confermaEliminazione() {

    /* <Modal title="Conferma eliminazione" :actions="confermaEliminazione.actions" :canDismiss="true"
        size="sm" @ready="(t) => confermaEliminazione.modal = t">
        <p>Confermi di voler eliminare il contenuto?</p>
    </Modal>*/

    um.msgbox({
        title : "Conferma eliminazione",
        content : `Confermi di voler eliminare il contenuto?`,
        actions : [
        {
            text : 'Confermo, elimina il contenuto',
            class : 'btn-danger',
            callback : function() {
                request({
                    task : "content/softDelete",
                    data : {
                        contentID : props.content.contentID,
                        permalink : props.content.permalink
                    },
                    callback : function() {
                        emit('deleted')
                    }

                })
            }
        }
    ],
    })

}
</script>
<template>
    <div class="row" v-if="props.content">
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-lg-10">
                    <label>Titolo</label>
                    <input maxlength="65" type="text" class="form-control" v-model="props.content.title">
                </div>
                <div class="col-lg-2">
                    <label>Permalink</label>
                    <input type="text" disabled class="form-control" v-model="props.content.permalink">
                </div>
                <div class="col-lg-4">
                    <label>Tema</label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="themes.search"
                        onCreate="themes.create" v-model="props.content.themeID" />
                </div>
                <div class="col-lg-4">
                    <label>Argomento</label>
                    <SearchSelect :disabled="!content?.themeID" class="w-100 d-block" required placeholder="Cerca..."
                        task="topics.search" onCreate="topics.create" v-model="props.content.topicID"
                        :data="{themeID: props.content.themeID}" />
                </div>
                <div class="col-lg-4">
                    <label>Tipologia</label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="typologies.search"
                        v-model="props.content.typologyID" />
                </div>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>Descrizione</label>
                        <textarea rows="5" class="form-control w-100" v-model="props.content.description"></textarea>
                        <small v-if="props.content.description">
                            {{ props.content.description.split(" | ")[0].length }} / 130 - Descr. breve | {{
                            props.content.description.replace(" | ","").length }} / 400 - Descr. lunga
                        </small>
                    </div>

                    <div class="form-group">
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

                    <div class="form-group">
                        <label>Meta</label><br>
                        <code>{{ props.content.meta }}</code>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div>
                        <label>Lingua</label>
                        <SearchSelect class="w-100" required placeholder="Cerca..." task="languages.search"
                            v-model="props.content.language" />
                    </div>

                    <div class="mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="standalone"
                                v-model="props.content.standalone">
                            <label class="form-check-label" for="standalone">Contenuto singolo</label>
                        </div>
                        <!-- <small v-if="props?.noStandalone">N.B.: Questo contenuto fa parte di un corso!</small> -->
                    </div>

                    <div class="mt-3">
                        <label>Autore</label>
                        <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="authors.search"
                            v-model="props.content.authorID" />
                    </div>

                    <div class="mt-3">
                        <label>Cliente</label>
                        <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="customers.search"
                            v-model="props.content.customerID" />
                    </div>
                </div>


            </div>
        </div>
        <div class="col-lg-4">
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

            <div class="card mt-3" >
                <div class="card-body">
                    <Media :content="props.content" />

                </div>
            </div>

            <div class="card mt-3" >
                <div class="card-body">
                    <div class="form-group">

                        <label>Stato contenuto</label>
                        <select class="form-select" v-model="props.content.draft">
                            <option value="0">Pubblicato</option>
                            <option value="1">Bozza</option>
                        </select>

                    </div>

                    <hr>

                    <button class="btn btn-outline-danger" @click="confermaEliminazione"><i class="bi bi-trash"></i> Elimina contenuto</button>

                </div>
            </div>

        </div>
    </div>
</template>