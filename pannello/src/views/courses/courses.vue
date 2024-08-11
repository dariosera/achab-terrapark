<script setup>
import {request, AgGridVue, quickTable, useRouter} from 'kadro-core'
import { reactive, ref } from 'vue';

const router = useRouter()
const tdata = reactive({rowData : [], columnDefs: []})

const testoNoRisultati = ref(null);

const getContents = () => {
        request({
        task: "courses/get",
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
                        'draft' : {
                            hide: true,
                        }
                    },
                    actions: [
                        {
                            btnText: `<i class="bi bi-pen"></i><span class="d-none d-xl-inline"> Modifica</span>`,
                            btnActionParams: ['permalink'],
                            btnAction: function (p) {

                                router.push(`/course/${p.permalink}`)

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

const nuovoCorso = () => {

request({
    task: 'course/create',
    data: {},
    callback: function (dt) {
        router.push(`/course/${dt.permalink}`)
    }
})


}




</script>
<template>
    <div>
        <nav class="navbar border-bottom" data-bs-theme="light">
            <div class="container-fluid">

                <ol class="breadcrumb my-2">
                    <li class="breadcrumb-item"><router-link to="/courses"><i class="bi bi-collection"></i> Corsi</router-link></li>
                </ol>

                <div class="ms-auto">
                    <button class="btn btn-sm btn-outline-primary" @click="nuovoCorso()"><i class="bi bi-plus"></i>
                        Nuovo corso</button>
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

</template>