<script setup>
import {useRoute, request, AgGridVue, quickTable, SearchSelect, useRouter, Modal} from 'kadro-core'
import { reactive, ref, watch } from 'vue';
import Contenuto from './Contenuto.vue'

const route = useRoute()
const router = useRouter()

const tdata = reactive({rowData : [], columnDefs: []})
const testoNoRisultati = ref(null);

const getContents = () => {
        request({
        task: "contents/get",
        data: {},
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
                        'standalone' : {
                            headerName : "Contenuto singolo",
                        },
                        'draft' : {
                            hide: true,
                        }
                    },
                    actions: [
                        {
                            btnText: `<i class="bi bi-pen"></i><span class="d-none d-xl-inline"> Modifica</span>`,
                            btnActionParams: ['permalink'],
                            btnAction: function (p) {

                                request({
                                    task: 'content/get',
                                    data: {
                                        permalink: p.permalink
                                    },
                                    callback: function (dt) {
                                        modificaContenuto.content = { ...dt }
                                        modificaContenuto.content.media = JSON.parse(modificaContenuto.content.media)
                                        modificaContenuto.content.meta = JSON.parse(modificaContenuto.content.meta)
                                        modificaContenuto.modal.show()
                                    }
                                })


                            }
                        },
]
});

        }
    })
}
getContents()



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
                }

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
                        modificaContenuto.content = null;
                        modificaContenuto.modal.hide()
                        getContents()
                    }
                })
            }
        },
    ]
})

const nuovoContenuto = () => {

    request({
        task: 'content/create',
        data: {},
        callback: function (dt) {
            
            modificaContenuto.content = {
                newContent : true,
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
                authors : []
            }
            modificaContenuto.modal.show()

            modificaContenuto.modal.show()
        }
    })

    
}


</script>
<template>
    <div>
        <nav class="navbar border-bottom" data-bs-theme="light">
            <div class="container-fluid">
                <span class="navbar-text"><i class="bi bi-collection"></i> Elenco contenuti</span>


                <div class="ms-auto">
                    <button class="btn btn-sm btn-outline-primary" @click="nuovoContenuto()"><i class="bi bi-plus"></i>
                        Nuovo contenuto</button>
                </div>
            </div>
        </nav>
        <div class="row m-2">
            <div class="col-12">
                <div class="card">

                    <AgGridVue v-if="tdata.rowData.length>0" ref="grid" @grid-ready="autoSize"
                        class="ag-theme-quartz ag-full-width" :autoSizeStrategy="{type: 'fitCellContents'}"
                        pagination="true" style="height: calc(100vh - 150px)" :rowData="tdata.rowData"
                        :columnDefs="tdata.columnDefs"></AgGridVue>
                    <div class="text-center testo-no-risultati" v-if="tdata.rowData.length === 0">
                        {{ testoNoRisultati }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Modal title="Modifica contenuto" :actions="modificaContenuto.actions" @hidden="modificaContenuto.onHidden" :canDismiss="false"
        size="fullscreen-xxl-down" @ready="(t) => modificaContenuto.modal = t">
        <Contenuto 
            v-if="modificaContenuto.content"
            :content="modificaContenuto.content"
            @deleted="() => {modificaContenuto.modal.hide(); modificaContenuto.content = null; getContents(); }" 
            />
    </Modal>

</template>