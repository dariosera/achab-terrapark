<script setup>
import {SearchSelect, request, upload, useModals} from 'kadro-core';
import {reactive, defineProps, onMounted} from 'vue';
import Media from './Media.vue';
import {Tooltip} from 'bootstrap'

const emit = defineEmits(["deleted"])

const um = useModals()

const props = defineProps(["content","courseEditor"]);

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

const newAuthor = reactive({
    add : function() {
       request({
        task : "content/addAuthor",
        data : {
            permalink : props.content.permalink,
            authorID : newAuthor.id,
        },
        callback : function(dt) {
            newAuthor.id = undefined;
            props.content.authors = dt;
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

const deleteAuthor = (id) => {
    request({
        task : "content/removeAuthor",
        data : {
            permalink : props.content.permalink,
            authorID : id,
        },
        callback : function(dt) {
            props.content.authors = dt;
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

onMounted(() => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl))
})
</script>
<template>  
    <div class="row" v-if="props.content">
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-md-8">
                    <label>Titolo</label>
                    <input maxlength="65" type="text" class="form-control" v-model="props.content.title">
                </div>
                <div class="col-md-4">
                    <label>Permalink</label>
                    <input type="text" disabled class="form-control" v-model="props.content.permalink">
                </div>
                <div v-if="!props.courseEditor" class="col-md-4">
                    <label>Tema</label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="themes.search"
                        onCreate="themes.create" v-model="props.content.themeID" />
                </div>
                <div v-if="!props.courseEditor" class="col-md-4">
                    <label>Argomento <span data-bs-toggle="tooltip" data-bs-custom-class="help-tooltip" data-bs-placement="bottom" title="È possibile lasciare vuoto il campo Argomento ed indicare solo il tema. In questo caso, il contenuto viene considerato trasversale a più argomenti e comparirà nel catalogo indipendentemente dall'argomento filtrato."  class="badge badge-pill text-dark border p-1 mb-1"><i class="bi bi-info"></i></span></label>
                    <SearchSelect :disabled="!content?.themeID" class="w-100 d-block" required placeholder="Cerca..."
                        task="topics.search" onCreate="topics.create" v-model="props.content.topicID"
                        :data="{themeID: props.content.themeID}" />
                    
                </div>
                <div class="col-md-4" :class="{'order-3':props.courseEditor}">
                    <label>Tipologia</label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="typologies.search"
                        v-model="props.content.typologyID" />
                </div>
                <div class="col-md-6" v-if="!props.courseEditor">
                    <div class="form-group">
                        <label>Descrizione</label>
                        <textarea rows="5" class="form-control w-100" v-model="props.content.description"></textarea>
                        <small v-if="props.content.description">
                            {{ props.content.description.split(" | ")[0].length }} / 130 - Descr. breve | {{
                            props.content.description.replace(" | ","").length }} / 400 - Descr. lunga
                        </small>
                    </div>
                </div>

                <div class="form-group card p-3 col-md-6" :class="{'order-2':props.courseEditor}">
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
                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-plus"></i> Aggiungi</button>
                    </form>
                </div>

                <div class="col-md-3"  :class="{'order-4':props.courseEditor}">
                    <div>
                        <label>Lingua</label>
                        <SearchSelect class="w-100" required placeholder="Cerca..." task="languages.search"
                            v-model="props.content.language" />
                    </div>
                </div>

                <div class="col-md-3"  :class="{'order-5':props.courseEditor}">
                    <label>Cliente <span data-bs-toggle="tooltip" data-bs-custom-class="help-tooltip" data-bs-placement="bottom" title="Per i contenuti che devono essere disponibili per tutti i clienti, lasciare vuoto questo campo."  class="badge badge-pill text-dark border p-1 mb-1"><i class="bi bi-info"></i></span></label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="customers.search"
                        v-model="props.content.customerID" />


                </div>

                <div class="col-md-6"  :class="{'order-2':props.courseEditor}">
                    <div class="card p-3">
                        <label>Autori</label>

                    <div class="tag-area mt-2">
                        <span v-for="author,i in props.content.authors" :key="i"
                            class="badge border text-muted px-2 me-2">
                            {{ author.name }} {{ author.surname }}
                            <i style="cursor: pointer" @click="deleteAuthor(author.authorID)"
                                class="bi bi-x-lg ms-2"></i></span>

                        <span v-if="props.content.authors.length === 0">
                            Nessun autore
                        </span>
                    </div>
                    <form @submit.prevent="newAuthor.add" class="d-flex mt-3">
                        <SearchSelect class="me-1 author-search-select" required placeholder="Aggiungi autore"
                            task="authors.search" v-model="newAuthor.id" />
                        <button class="btn btn-outline-primary" type="submit"><i class="bi bi-plus"></i> Aggiungi</button>
                    </form>
                    </div>
                    
                </div>

                
               

                

                <div class="col-md-4 form-group order-last">
                    <label>Meta</label><br>
                    <code>{{ props.content.meta }}</code>
                </div>


            </div>
        </div>
        <div class="col-lg-4">
            <div v-if="!props.courseEditor" class="card">
                <div class="card-body">
                    <label>Immagine di anteprima <span data-bs-toggle="tooltip" data-bs-custom-class="help-tooltip" data-bs-placement="bottom" title="L'immagine deve essere in formato JPG e con rapporto di proporzioni 16:9."  class="badge badge-pill text-dark border p-1 mb-1"><i class="bi bi-info"></i></span></label>

                    <form @submit.prevent="doUpload" class="d-flex my-2" v-if="props.content.image_url==null">
                        <input required type="file" class="form-control form-control-sm me-2" accept="image/jpeg">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i></button>
                    </form>
                    <div v-else>
                        <img :src="props.content.image_url+'?t='+(new Date()).getTime()" class="w-50 d-block">
                        <button class="btn btn-sm btn-link" @click="() => props.content.image_url = null">Cambia
                            immagine</button>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <Media :content="props.content" />

                </div>
            </div>

            <div v-if="!props.courseEditor" class="card mt-3">
                <div class="card-body">
                     <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="standalone"
                            v-model="props.content.standalone">
                        <label class="form-check-label" for="standalone">Contenuto singolo</label>
                    </div>
                </div>
            </div>


            <div class="card mt-3">
                <div class="card-body">
                    <div class="form-group">

                        <label>Stato contenuto</label>
                        <select class="form-select" v-model="props.content.draft">
                            <option value="0">Pubblicato</option>
                            <option value="1">Bozza</option>
                        </select>

                    </div>

                    <hr>

                    <button class="btn btn-outline-danger" @click="confermaEliminazione"><i class="bi bi-trash"></i>
                        Elimina contenuto</button>

                </div>
            </div>

        </div>
    </div>
</template>
<style lang="scss">
.search-select input {
    max-width: 10rem!important;
}

.help-tooltip {
    font-size: 11px;
}

</style>