<script setup>
import {useRoute, useRouter, request, Modal, quickTable, AgGridVue} from 'kadro-core';
import {reactive, ref, onMounted, watch} from 'vue'
import Info from './Info.vue';
import Contenuto from '../contents/Contenuto.vue';
import Correlati from './Correlati.vue';
import Certificato from './Certificato.vue';

const unsavedChanges = ref(false);
const route = useRoute();
const router = useRouter();
const content = reactive({
    permalink : route.params.permalink,
    title : "",
    language : null,
    standalone : false,
    media : {},
    tags : [],
    topicID : "",
    themeID : "",
    typologyID : "",
    image : "",
    description : "",
    previews : {},
    draft : 1,
    customerID: null,
    isCourse : 1
});


let autoloopSave = null;

const courseContents = ref([])

onMounted(() => {
    request({
        task : "course/get",
        data : {
            permalink : route.params.permalink
        },
        callback : function(dt) {
            Object.assign(content, dt);
            getCourseContents();

            watch(content,() => {
                unsavedChanges.value = true
                if (autoloopSave !== null) {
                    window.clearTimeout(autoloopSave);
                }
                autoloopSave = window.setTimeout(() => salvaCorso(true),1000);
                
            },{deep: true})
        }
    })
})

const getCourseContents = () => {
    request({
        task : "course/getContents",
        data : {
            courseID : content.contentID,
        },
        callback : function(dt) {
            courseContents.value = dt;  
        }
    })
}

const cercaContenuto = reactive({
    modal : null,
    searchString : null,
    actions : [
        {
            text : "Seleziona almeno un contenuto",
            class : "btn-primary",
            callback : function() {

                request({
                    task : "course/addContents",
                    data : {
                        courseID : content.contentID,
                        contentList : cercaContenuto.selectedContents.map(x => x.contentID),
                    },
                    callback : function() {
                        getCourseContents()
                    }
                })

            }
        }
    ],
    selectionChanged : function() {
        cercaContenuto.selectedContents = grid.value.api.getSelectedRows()
        if (cercaContenuto.selectedContents.length > 0)
            cercaContenuto.actions[0].text = `Aggiungi ${cercaContenuto.selectedContents.length} contenut${cercaContenuto.selectedContents.length==1?'o':'i'}`
        else 
            cercaContenuto.actions[0].text = "Seleziona almeno un contenuto"
    },
    selectedContents : []
})

const tdata = reactive({rowData : [], columnDefs: [], rowSelection : 'multiple'})
const testoNoRisultati = ref(null);
const grid = ref(null);

const getContents = () => {
        if (cercaContenuto.searchString.length < 4) return;
        request({
        task: "contents/search",
        data: {
            s : cercaContenuto.searchString,
            checkIfIsInCourse : content.contentID,
        },
            callback: function (dt) {
                if (dt.length === 0) {
                    testoNoRisultati.value = "Nessun contenuto trovato con il filtro impostato";
                }
                quickTable(tdata, dt, {
                    setToAllColumns: {
                        filter: true,
                        select: true
                    },
                    columnDefs : {
                        'contentID' : {
                            hide : true,
                        },
                        'permalink' : {
                            checkboxSelection: true,
                            //headerCheckboxSelection: true,
                            //showDisabledCheckboxes: true,
                        },
                        'standalone' : {
                            headerName : "Contenuto singolo",
                        },
                        
                    },
                    });

                    //tdata.columnDefs.unshift({field : "",  });

        }
    })
}

const removeContent = (id) =>{
    request({
        task : "course/removeContent",
        data : {
            contentID: id,
            courseID : content.contentID,
        },
        callback : function() {
            getCourseContents()
        }
    })
}

function move(i, dir) {
    let array = courseContents.value;
  // Check if the move is within bounds
  if (i < 0 || i >= array.length || (dir !== -1 && dir !== 1)) {
    console.log('Invalid index or direction');
    return;
  }

  // Calculate the new index
  const newIndex = i + dir;

  // Ensure the new index is within array bounds
  if (newIndex < 0 || newIndex >= array.length) {
    console.log('Move is out of bounds');
    return;
  }

  // Swap elements
  [array[i], array[newIndex]] = [array[newIndex], array[i]];

  const newOrder = [];
  array.forEach((c,i) => {
    newOrder.push({
        contentID : c.contentID,
        customOrder : i
    })
  })
  request({
    task : "course/updateOrder",
    data : {
        newOrder,
        courseID : content.contentID,
    },
    callback: function() {
        getCourseContents();
    }
  })
}

const modificaContenuto = reactive({
    modal : null,
    content : null,
    actions : [
    {
            text : "Esci senza salvare",
            class : 'btn-outline-secondary',
            callback : function() {

                if (modificaContenuto.content.newContent === true) {
                    request({
                        task : "content/delete",
                        data : {
                            permalink : modificaContenuto.content.permalink
                        },
                        callback : function() {
                            modificaContenuto.content = null;
                            modificaContenuto.modal.hide()
                        }
                    })
                } else {
                    modificaContenuto.content = null;
                }salvaCorso

                //this.modal.hide()
            }
        },
        {
            text : "Salva",
            class : 'btn-success',
            callback : function() {
                request({
                    task : "content/update",
                    data : modificaContenuto.content,
                    callback : function() {
                        
                        if (modificaContenuto.content.newContent === true) {
                            request({
                                task : "course/addContents",
                                data : {
                                    courseID : content.contentID,
                                    contentList : [
                                        modificaContenuto.content.contentID
                                    ]
                                },
                                callback : function() {
                                    modificaContenuto.content = null;
                                    modificaContenuto.modal.hide()
                                    getCourseContents()
                                }
                            })
                        } else {
                            modificaContenuto.content = null;
                            modificaContenuto.modal.hide()
                            getCourseContents()
                        }
                        

                    }
                })
            }
        },
    ],
    esistente : function(permalink) {
        request({
            task: 'content/get',
            data: {
                permalink
            },
            callback: function (dt) {
                modificaContenuto.content = { ...dt }
                modificaContenuto.content.media = JSON.parse(modificaContenuto.content.media)
                modificaContenuto.content.meta = JSON.parse(modificaContenuto.content.meta)
                modificaContenuto.modal.show()
            }
        })
    }
})

const nuovoContenuto = () => {

    request({
        task: 'content/create',
        data: {},
        callback: function (dt) {
            
            modificaContenuto.content = {
                newContent : true,
                contentID : dt.contentID,
                permalink : dt.permalink,
                title : "",
                language : null,
                standalone : false,
                media : {},
                tags : [],
                topicID : "",
                themeID : "",
                typologyID : "",
                image : "",
                description : "",
                previews : {},
                draft : 1,
                customerID: null,
                authors: [],
            }
            modificaContenuto.modal.show()

            modificaContenuto.modal.show()
        }
    })

    
}

const salvaCorso = (autoSave) => {
    request({
        task : "course/update",
        data : content,
        hideLoader: autoSave === true,
        callback : function() {
            unsavedChanges.value = false

            if (autoloopSave !== null) {
                window.clearTimeout(autoloopSave);
                autoloopSave = null;
            }
            
        }

    })
}

const eliminaCorso = () => {
    request({
        task : "content/softDelete",
        data : {
            permalink : content.permalink
        },
        callback : function() {
            router.push("/courses")
        }

    })
}

</script>
<template>
    <div>
        <nav class="navbar border-bottom" data-bs-theme="light">
            <div class="container-fluid">
                
                <ol class="breadcrumb my-2">
                    <li class="breadcrumb-item"><router-link to="/courses">Corsi</router-link></li>
                    <li class="breadcrumb-item active" aria-current="page">{{content.title}}</li>
                </ol>

                <div class="ms-auto">
                    <div class="d-flex align-items-center">
                        <label class="me-2">Stato: </label>
                        <select class="form-select me-3" v-model="content.draft">
                            <option value="0">Pubblicato</option>
                            <option value="1">Bozza</option>
                        </select>

                        <button v-if="unsavedChanges" class="btn btn-outline-primary btn-save" disabled>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            Salvataggio...
                        </button>
                        <button v-else class="btn btn-outline-primary btn-save" @click="salvaCorso"><i
                                class="bi bi-save"></i> {{ content.draft == 1 ? 'Salva bozza' : 'Salva' }}</button>


                        <div class="dropdown">
                            <a class="btn btn-link dropdown-toggle no-arrow" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" @click="eliminaCorso"><i class="bi bi-trash"></i> Elimina corso</a></li>
                            </ul>
                        </div>

                    </div>
                </div>

            </div>
        </nav>
        <div class="row m-2">
            <div class="col-xl-4">
                <Info :content="content" />

                <Certificato :course="route.params.permalink"></Certificato>
            </div>
            <div class="col-xl-8">
               <h3>Contenuti del corso</h3>

                <div v-if="courseContents.length === 0" class="text-center">
                    <div>Nessun contenuto</div>
                </div>
                <div v-else class="content-list">
                    <div v-for="(subc, i) in courseContents" :key="i" class="content" :class="{'draft' : subc.draft == 1}">
                        <div class="updown">
                            <div class="btn-group-vertical btn-group-sm" role="group"
                                aria-label="Vertical button group">
                                <button type="button" class="btn btn-link" @click="move(i,-1)"><i
                                        class="bi bi-chevron-up"></i></button>
                                <button type="button" class="btn btn-link" @click="move(i,1)"><i
                                        class="bi bi-chevron-down"></i></button>

                            </div>
                        </div>
                        <div class="info">
                            <div><span v-if="subc.draft == 1">Bozza -</span> {{ i+1 }}. <strong>{{ subc.title }}</strong></div>
                            <div><em>{{ subc?.description?.split("|")[0] || 'Senza descrizione' }}</em></div>
                            <div>Tipologia: {{ subc.typology || "-" }} | Tema: {{ subc.theme || "-" }} | Argomento: {{
                                subc.topic || "-" }}</div>
                        </div>
                        <div class="actions">
                            <button class="d-block btn btn-sm btn-outline-danger"
                                @click="removeContent(subc.contentID)">
                                <i class="bi bi-trash"></i> Rimuovi dal corso
                            </button>

                            <button class="d-block mt-1 btn btn-sm btn-outline-primary"
                                @click="modificaContenuto.esistente(subc.permalink)">
                                <i class="bi bi-pencil"></i> Modifica
                            </button>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-primary me-2" @click="cercaContenuto.modal.show"><i class="bi bi-search"></i>
                        Inserisci un contenuto esistente</button>
                    <button class="btn btn-outline-primary" @click="nuovoContenuto"><i class="bi bi-plus"></i> Crea un
                        nuovo contenuto</button>
                </div>

                <hr>

                <h3>Contenuti correlati</h3>

                <Correlati :course="route.params.permalink" />
            </div>
        </div>
    </div>

    <Modal title="Cerca un contenuto" :actions="cercaContenuto.actions" @hidden="cercaContenuto.onHidden"
        :canDismiss="true" size="xl" @ready="(t) => cercaContenuto.modal = t">

        <div>
            <div class="d-flex mb-3">
                <input @keyup="getContents" required type="text" class="form-control me-2"
                    v-model="cercaContenuto.searchString" placeholder="Cerca titolo o permalink">
            </div>

            <AgGridVue v-if="tdata.rowData.length>0" ref="grid" @grid-ready="autoSize"
                class="ag-theme-quartz ag-full-width" :autoSizeStrategy="{type: 'fitCellContents'}" pagination="true"
                style="height: 300px" :rowData="tdata.rowData" :columnDefs="tdata.columnDefs"
                :rowSelection="tdata.rowSelection" @selection-changed="cercaContenuto.selectionChanged"></AgGridVue>
            <div class="text-center testo-no-risultati" v-if="tdata.rowData.length === 0">
                {{ testoNoRisultati }}
            </div>
        </div>
    </Modal>

    <Modal title="Modifica contenuto" :actions="modificaContenuto.actions" @hidden="modificaContenuto.onHidden"
        :canDismiss="false" size="fullscreen-xxl-down" @ready="(t) => modificaContenuto.modal = t">
        <Contenuto :courseEditor="true" v-if="modificaContenuto.content" :content="modificaContenuto.content"
            @deleted="() => {modificaContenuto.modal.hide(); modificaContenuto.content = null; getCourseContents(); }" />
    </Modal>
</template>
<style lang="scss" scoped>
.content-list {
    .content {
        display: flex;
        padding: .4rem;

        &.draft {
            .img, .info {
                opacity: .6;
            }
            
        }

        &:not(:last-child) {
            border-bottom: 1px solid rgba(var(--bs-body-color-rgb),.2);
        }

        .updown {
            padding-right: .4rem;
            display: flex;
            flex-direction: column;

            .btn-group-vertical {
                height: 100%;
            }
        }

        .img {
            width: 160px;
            height: 90px;
            overflow: hidden;


            
            img {
                display: block;
                object-fit: cover;
            }

        }

        .info {
            padding-left: .5rem;
            flex: 1;
        }
    }
}

.btn-save {
    white-space: nowrap;
}

.no-arrow::after {
    display: none;
}
</style>