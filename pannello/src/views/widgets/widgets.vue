<script setup>
import {request, AgGridVue, quickTable, useRouter, Modal, useModals, SearchSelect} from 'kadro-core'
import { reactive, ref } from 'vue';

const router = useRouter()
const tdata = reactive({rowData : [], columnDefs: []})
const testoNoRisultati = ref(null);

const frontendUrl = import.meta.env.VITE_TERRAPARK_FRONTEND;

const ui = useModals();

const getWidgets = () => {
        request({
        task: "widgets/get",
        data: {},
            callback: function (dt) {
                if (dt.length === 0) {
                    testoNoRisultati.value = "Nessun widget trovato";
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
                            btnText: `<i class="bi bi-display"></i><span class="d-none d-xl-inline"> Anteprima</span>`,
                            btnActionParams: ['widgetID'],
                            btnAction: function (p) {

                                ui.msgbox({
                                    title : "Anteprima",
                                    content : `<iframe style="border: 1px solid grey; width: 100%; height: 600px;" src="${frontendUrl}/embed.html?id=${p.widgetID}"></iframe>`,
                                    size: "xl"
                                })

                            }
                        },
                        {
                            btnText: `<i class="bi bi-pen"></i><span class="d-none d-xl-inline"> Modifica</span>`,
                            btnActionParams: ['widgetID'],
                            btnAction: function (p) {

                                request({
                                    task: "widgets/single",
                                    data : {
                                        widgetID : p.widgetID
                                    },
                                    callback : function(dt) {
                                        modificaWidget.data = dt;
                                        modificaWidget.modal.show()
                                    }
                                })
                            }
                        },
]
});

        }
    })
}
getWidgets()


const modificaWidget = reactive({
    modal : null,
    data : null,
    actions : [
        {
            text : "Salva",
            class : "btn-primary",
            callback : function() {
                request({
                    task : "widgets/update",
                    data : modificaWidget.data,
                    callback: function() {
                        getWidgets()
                    }
                })
            }
        }
    ],
    htmlcode : () => {

        if (modificaWidget.data.new) {
            return `Salva il widget per ottenere il codice`
        }

        const conf = new URLSearchParams();

        conf.append("id",modificaWidget.data.widgetID);
        if (modificaWidget.iframeconf.width) {
            conf.append("width",modificaWidget.iframeconf.width)
        }
        if (modificaWidget.iframeconf.height) {
            conf.append("height",modificaWidget.iframeconf.height)
        }
        if (modificaWidget.iframeconf.title !== true) {
            conf.append("title","false")
        }

        return `<iframe src="${frontendUrl}/embed.html?${conf.toString()}"></iframe>`
    },
    iframeconf : {
        title : true
    }
})

const nuovoWidget = () => {
    modificaWidget.data = {
        new : true,
        title : "",
        projectID : undefined,
        authorized_domains : "[*]",
        config : {
            mode : 'new'
        }
    }
    modificaWidget.modal.show()
}
</script>
<template>
    <div>
        <nav class="navbar border-bottom" data-bs-theme="light">
            <div class="container-fluid">

                <ol class="breadcrumb my-2">
                    <li class="breadcrumb-item"><i class="bi bi-person-vcard"></i> Widgets</li>
                </ol>

                <div class="ms-auto">
                    <button class="btn btn-sm btn-outline-primary" @click="nuovoWidget"><i class="bi bi-plus"></i>
                        Nuovo widget</button>
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

    <Modal title="Configura widget" :actions="modificaWidget.actions" :canDismiss="true" size="lg"
        @ready="(t) => modificaWidget.modal = t">

        <div class="row g-2" v-if="modificaWidget.data">
            <div class="col-lg-5">
                <div class="form-group">
                    <label>Titolo</label>
                    <input type="text" class="form-control" v-model="modificaWidget.data.title">
                </div>
                <div class="form-group">
                    <label>Domini autorizzati</label>
                    <input type="text" class="form-control" v-model="modificaWidget.data.authorized_domains">
                </div>
                <div class="form-group">
                    <label>Progetto</label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="projects.search"
                        v-model="modificaWidget.data.projectID" />
                </div>
            </div>
            <div class="col-lg-7">
                <div class="form-group">
                    <label>Modalità widget</label>
                    <select class="form-select" v-model="modificaWidget.data.config.mode">
                        <option value="new">Mostra ultimi contenuti caricati</option>
                        <option value="course">Mostra un corso specifico</option>
                        <option value="single">Mostra un contenuto specifico</option>
                        <option value="typology">Contenuti per tipologia</option>
                    </select>
                </div>

                <div v-if="modificaWidget.data.config.mode == 'course'" class="form-group">
                    <label>Permalink del corso</label>
                    <input type="text" class="form-control" v-model="modificaWidget.data.config.course_data">
                </div>

                <div v-if="modificaWidget.data.config.mode == 'single'" class="form-group">
                    <label>Permalink del contenuto</label>
                    <input type="text" class="form-control" v-model="modificaWidget.data.config.single_data">
                </div>

                <div v-if="modificaWidget.data.config.mode == 'typology'" class="form-group">
                    <label>Tipologia</label>
                    <SearchSelect class="w-100 d-block" required placeholder="Cerca..." task="typologies.search"
                        v-model="modificaWidget.data.config.typology_data" />
                </div>

                <hr>

                <h5>Codice di incorporamento</h5>

                <div class="row">
                    <div class="col-4">
                        <input type="number" class="form-control" placeholder="width"
                            v-model="modificaWidget.iframeconf.width">
                    </div>
                    <div class="col-4">
                        <input type="number" class="form-control" placeholder="height"
                            v-model="modificaWidget.iframeconf.height">
                    </div>
                    <div class="col-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="mostratitolo" v-model="modificaWidget.iframeconf.title">
                            <label class="form-check-label" for="mostratitolo">Mostra titolo</label>
                        </div>

                    </div>
                </div>
                <textarea class="form-control mt-3 w-100" readonly :value="modificaWidget.htmlcode()"></textarea>


            </div>
        </div>
    </Modal>
</template>