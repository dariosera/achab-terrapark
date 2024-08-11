<script setup>
import {request, Modal, useToasts, quickTable, AgGridVue, SearchSelect, useRouter} from 'kadro-core'
import { onMounted, ref, reactive,watch } from 'vue';

import Stellina from './Stellina.vue';

const tdata = reactive({rowData:[], columnDefs: []});

const router = useRouter();
const myGrid = ref(null)
const mostraArchiviati = ref(false)
const nArchiviati = ref(0);

const progettoDaClonare = ref(null)

const quickFilter = reactive({
    query : ""
})
let lazy_filter = ref(null)
function applyQuickFilter() {
    
    if (lazy_filter.value !== null) {
        window.clearTimeout(lazy_filter.value);
        lazy_filter.value = null;
    }
    lazy_filter.value = setTimeout(() => {
        myGrid.value.api.setGridOption('quickFilterText', quickFilter.query);
        myGrid.value.api.autoSizeAllColumns()
        lazy_filter.value = null;
    },500);
    
}


onMounted(() => {

    getProgetti()


})


const getProgetti = () => {
    request({
        task: "projects.get",
        data : {
            mostraArchiviati : mostraArchiviati.value,
        },
        callback: function (dt) {

            // let today = (new Date()).toISOString()
            // dt.map(x => {
            //     x.attivo = (x.inizio_iscrizioni <= today && x.fine_iscrizioni >= today)
            // })
            nArchiviati.value = dt.archived===null?0:dt.archived;

            quickTable(tdata,dt.projects,{
                columnDefs: {
                    preferito : {
                        headerName : "",
                        cellRenderer : Stellina,
                        sortable: false,
                        sort: "desc",
                        maxWidth: 40
                    },
                    customerID : {
                        hide: true,
                    },
                    customer : {
                        headerName : "Cliente",
                    },
                    projectID : {
                        hide: true
                    },
                    title : {
                        headerName : "Titolo progetto",
                    },
                    start_date : {
                        headerName : "Inizio progetto",
                        cellDataType : "dateString",
                        cellRenderer: c => {
                            if (c.value===null) return 'Non avviato'
                            else
                                return (new Date(c.value)).toLocaleDateString()
                        }
                    },
                    end_date : {
                        headerName : "Fine progetto",
                        cellDataType : "dateString",
                        cellRenderer: c => {
                            if (c.value===null) return '-'
                            else
                                return (new Date(c.value)).toLocaleDateString()
                        }
                    },
                    attivo : {
                        headerName : "In corso",
                        filter: true
                    }
                },
                actions : [
                    // {
                    //     btnText : `<i class="bi bi-copy" title="Duplica il progetto"></i>`,
                    //     btnActionParams: ['IDprogetto', 'titolo_progetto', 'cliente'],
                    //     btnAction: function(p) {
                    //         progettoDaClonare.value = p
                    //         nuovoProgetto.show()
                    //         // nuovoProgetto.bsmodal.getInstance().addEventListener("hidden.bs.modal",function() {
                    //         //     alert("hidden")
                    //         // }, {once: true})
                    //     }
                    // },
                    // {
                    //     btnText : `<i class="bi bi-list"></i><span class="d-none d-xl-inline"> Elenco iscrizioni</span>`,
                    //     btnActionParams: ['IDprogetto'],
                    //     btnAction: function(p) {
                    //         router.push(`/iscrizioni/${p.IDprogetto}`)
                    //     }
                    // },
                    {
                        btnText : `<i class="bi bi-gear"></i><span class="d-none d-xl-inline"> Configura progetto</span>`,
                        btnActionParams: ['projectID'],
                        btnAction: function(p) {
                            router.push(`/project/${p.projectID}`)
                        }
                    },
                ]
            })
            
        }
    })
}

watch(mostraArchiviati,getProgetti)


const nuovoProgetto = reactive({
    visible: false,
    show : function() { 
        nuovoProgetto.bsmodal.show()
        if (progettoDaClonare.value !== null) {
            nuovoProgetto.clone = progettoDaClonare.value;
        }
    },
    titolo_progetto : "",
    IDcliente : null,
    ready : (modal) => {
        nuovoProgetto.bsmodal = modal;
    },
    onHidden : function() {
        progettoDaClonare.value = null
    },
    bsmodal : {},
    actions : [
        {
            text : "Aggiungi",
            class : "btn-primary",
            callback : function() {
                request({
                    task : "project/add",
                    data : {
                        customerID : nuovoProgetto.customerID,
                        title: nuovoProgetto.title,
                        clone : nuovoProgetto.clone,
                    },
                    callback : function() {
                        useToasts().addToast({
                            title : "",
                            content : `Il progetto <b>${nuovoProgetto.titolo_progetto}</b> è stato aggiunto.`
                        })

                        getProgetti()
                        nuovoProgetto.title = "";
                        nuovoProgetto.customerID = "";
                    }
                })
            }
        }
    ]
})

const gridReady = () => {
    myGrid.value.api.addEventListener('toggleFavorite',() => {
        console.log("ok")
        myGrid.value.api.refreshCells()
        myGrid.value.api.onSortChanged()
    })
}
</script>
<template>
    <div>
        <div class="card">
            <div class="card-body pt-1">
                <div class="row align-items-center">
                    <div class="col align-center">
                        <h5 class="card-title m-0">Progetti</h5>
                    </div>
                    <div class="col-auto d-flex p-2">
                        <button class="btn btn-sm btn-outline-primary me-2" style="min-width: 9rem;" @click="nuovoProgetto.show()"><i class="bi bi-plus"></i> Nuovo
                    progetto</button>
                        <div class="input-group">
                            <input class="form-control form-control-sm no-outline" type="search"
                                placeholder="Ricerca rapida" v-model="quickFilter.query" @keyup="applyQuickFilter"
                                aria-label="Search">
                            <button v-if="lazy_filter !== null" class="btn btn-outline-secondary" type="button">
                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                <span class="visually-hidden" role="status">Loading...</span>
                            </button>
                        </div>
                        <div class="dropdown">
                            <a class="btn btn-link text-dark" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"  aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </a>
                            <div class="dropdown-menu p-2" style="width: 300px;">
                                <div class="form-check form-switch">
                                    <input v-model="mostraArchiviati" class="form-check-input" type="checkbox" role="switch" id="mostraArchiviati">
                                    <label class="form-check-label" for="mostraArchiviati">Mostra progetti archiviati</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <AgGridVue v-if="tdata.rowData.length>0" ref="myGrid" @grid-ready="gridReady"
                    class="ag-theme-quartz ag-full-width" :autoSizeStrategy="{type: 'fitCellContents'}" pagination="true"
                    style="height: calc(100vh - 250px)" :rowData="tdata.rowData" :columnDefs="tdata.columnDefs"></AgGridVue>
                <div v-else>
                    <small>Nessun progetto trovato</small>
                </div>
            </div>
            <div class="card-footer d-flex">
                
                <small class="text-end">Progetti totali: {{ tdata.rowData.length }} <br>
                    Progetti archiviati: {{ nArchiviati }}</small>
            </div>
        </div>
        <Modal title="Nuovo progetto" :actions="nuovoProgetto.actions" @hidden="nuovoProgetto.onHidden" canDismiss="true" size="lg"
            @ready="nuovoProgetto.ready">
            <div class="row">
                <div class="col-12" v-if="progettoDaClonare">
                    <div class="alert alert-danger">
                        Stai duplicando il progetto #{{ progettoDaClonare.IDprogetto }} - "<em>{{ progettoDaClonare.titolo_progetto }}</em>" del cliente <b>{{ progettoDaClonare.cliente }}</b>.
                    </div>
                </div>
                <div class="col-lg-4">
                    <label>Cliente</label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="customers.search"
                        v-model="nuovoProgetto.customerID" />
                </div>
                <div class="col-lg-8">
                    <div class="form-group">
                        <label>Nome del progetto</label>
                        <input type="text" class="form-control" v-model="nuovoProgetto.title">
                    </div>
                </div>
            </div>
        </Modal>

        
    </div>
</template>
<style lang="scss" scoped>
.clickable {
    cursor: pointer;
}
.card {
    min-height: calc(100vh - 100px);
}
</style>