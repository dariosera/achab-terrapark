<script setup>
import {request, upload, AgGridVue, quickTable, useRouter, Modal} from 'kadro-core'
import { reactive, ref } from 'vue';

const router = useRouter()
const tdata = reactive({rowData : [], columnDefs: []})
const testoNoRisultati = ref(null);

const getAuthors = () => {
        request({
        task: "authors/get",
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
                            btnActionParams: ['authorID'],
                            btnAction: function (p) {

                                request({
                                    task: "authors/single",
                                    data : {
                                        authorID : p.authorID
                                    },
                                    callback : function(dt) {
                                        modificaAutore.data = dt[0]
                                        modificaAutore.modal.show()
                                    }
                                })
                            }
                        },
]
});

        }
    })
}
getAuthors()


const modificaAutore = reactive({
    modal : null,
    data : null,
    actions : [
        {
            text : "Salva",
            class : "btn-primary",
            callback : function() {
                request({
                    task : "authors/update",
                    data : modificaAutore.data,
                    callback: function() {
                        getAuthors()
                    }
                })
            }
        }
    ]
})

const nuovoAutore = () => {
    modificaAutore.data = {
        new : true,
    }
    modificaAutore.modal.show()
}

const doUpload = function(e) {

console.log(e)
let formData = new FormData();
formData.append("file", e.target[0].files[0]);

upload({
    task : "authors/uploadPicture",
    data : formData,
    callback : function(dt) {
        modificaAutore.data.picture = dt.file_id;
        modificaAutore.data.picture_url = dt.s3.url;
    }
})

}
</script>
<template>
    <div>
        <nav class="navbar border-bottom" data-bs-theme="light">
            <div class="container-fluid">

                <ol class="breadcrumb my-2">
                    <li class="breadcrumb-item"><i class="bi bi-person-vcard"></i> Autori e relatori</li>
                </ol>

                <div class="ms-auto">
                    <button class="btn btn-sm btn-outline-primary" @click="nuovoAutore"><i class="bi bi-plus"></i>
                        Nuovo autore</button>
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

    <Modal title="Modifica autore" :actions="modificaAutore.actions"
        :canDismiss="true" size="lg" @ready="(t) => modificaAutore.modal = t">
        
        <div class="row g-2" v-if="modificaAutore.data">
            <div class="col-lg-7">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" class="form-control" v-model="modificaAutore.data.name">
                </div>
                <div class="form-group">
                    <label>Cognome</label>
                    <input type="text" class="form-control" v-model="modificaAutore.data.surname">
                </div>
                <div class="form-group">
                    <label>Ruolo</label>
                    <input type="text" class="form-control" v-model="modificaAutore.data.role">
                </div>
                <div class="form-group">
                    <label>Biografia</label>
                    <textarea class="form-control" v-model="modificaAutore.data.bio"></textarea>
                </div>
            </div>
            <div class="col-lg-5">
                <label>Immagine</label>

                <form @submit.prevent="doUpload" class="d-flex my-2" v-if="modificaAutore.data.picture_url==null">
                    <input required type="file" class="form-control form-control-sm me-2" accept="image/jpeg">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-upload"></i></button>
                </form>
                <div v-else>
                    <img :src="modificaAutore.data.picture_url" style="display:block; max-height: 200px;">
                    <button class="btn btn-sm btn-link" @click="() => modificaAutore.data.picture_url=null">Cambia immagine</button>
                </div>
            </div>
        </div>
    </Modal>
</template>