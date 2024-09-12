<script setup>
import {request, Modal, AgGridVue, quickTable} from 'kadro-core'
import {ref, reactive} from 'vue'

const props = defineProps(["course"])
const related = ref([])

function getRelatedContents() {
    request({
        task : "course/getRelatedContents",
        data : {
            course : props.course
        },
        callback : (dt) => {
            related.value = dt;
        }
    })
}
getRelatedContents()

const cercaContenuto = reactive({
    modal : null,
    searchString : null,
    actions : [
        {
            text : "Seleziona almeno un contenuto",
            class : "btn-primary",
            callback : function() {

                request({
                    task : "course/addRelated",
                    data : {
                        course : props.course,
                        contentList : cercaContenuto.selectedContents.map(x => x.permalink),
                    },
                    callback : function() {
                        getRelatedContents()
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
        task: "contents/searchStandalone",
        data: {
            s : cercaContenuto.searchString,
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
 

const removeContent = (permalink) =>{
    request({
        task : "course/removeRelated",
        data : {
            course: props.course,
            permalink : permalink,
        },
        callback : function() {
            getRelatedContents()
        }
    })
}

</script>
<template>

    <div v-if="related.length === 0">Nessun contenuto correlato</div>

    <div v-else class="content-list">
                    <div v-for="(subc, i) in related" :key="i" class="content" :class="{'draft' : subc.draft == 1}">
                       
                        <div class="info">
                            <div><span v-if="subc.draft == 1">Bozza -</span><strong>{{ subc.title }}</strong></div>
                            <div><em>{{ subc?.description?.split("|")[0] || 'Senza descrizione' }}</em></div>
                            <div>Tipologia: {{ subc.typology || "-" }} | Tema: {{ subc.theme || "-" }} | Argomento: {{
                                subc.topic || "-" }}</div>
                        </div>
                        <div class="actions">
                            <button class="d-block btn btn-sm btn-outline-danger"
                                @click="removeContent(subc.permalink)">
                                <i class="bi bi-trash"></i> Rimuovi dai correlati
                            </button>
                        </div>
                    </div>
                </div>


    <div class="text-center">
        <button class="btn btn-primary me-2" @click="cercaContenuto.modal.show"><i class="bi bi-search"></i>
            Aggiungi un contenuto ai correlati</button>
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
</style>