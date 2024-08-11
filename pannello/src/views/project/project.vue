<script setup>

import { onMounted, reactive, watch, ref } from 'vue';
import {useRoute, useRouter, request, SearchSelect, useToasts} from 'kadro-core';
import SliderHP from './SliderHP.vue';
import GestioneContenuti from './GestioneContenuti.vue';
import GestioneTema from './GestioneTema.vue';

const project = reactive({})

const r = useRoute();
const router = useRouter();
const id = r.params.id;


const getProject = () => {
    request({
        task : "project/get",
        data : {projectID : id},
        callback : function(dt) {
            Object.assign(project,dt[0])
        }
    });
}

onMounted(getProject)

const saveProject = () => {
    request({
        task : "project/update",
        data : project,
        callback: getProject,
    })
}
</script>
<template>

    <div v-if="project.archived==1" class="bg-danger text-center text-white">
        <span>Il project è archiviato. <button type="button" @click="archiviaproject(false)"
                class="btn btn-link text-white"><i class="bi bi-arrow-counterclockwise"></i> Riattiva
                project</button></span>
    </div>
    <nav class="navbar border-bottom" data-bs-theme="light">
        <div class="container-fluid">

            <ol class="breadcrumb my-2">
                <li class="breadcrumb-item"><router-link to="/customers">Clienti e progetti</router-link></li>
                <li class="breadcrumb-item active" aria-current="page">Progetto <b>{{project.title}}</b></li>
            </ol>

            <div class="ms-auto">
                <!-- <div class="d-flex align-items-center">
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

                    </div>-->
            </div>

        </div>
    </nav>

    <div class="p-3">

        <div class="row section">
            <div class="col-12 col-lg-4 col-xl-3">
                <h4>Informazioni sul progetto</h4>
            </div>

            <div class="col-12 col-lg-8 col-xl-9">
                <form @submit.prevent="saveProject">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informazioni sul progetto</h5>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group mt-2">
                                        <label>Titolo</label>
                                        <input :disabled="project.archived === 1" type="text" v-model="project.title"
                                            required class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mt-2">
                                        <label>Inizio progetto</label>
                                        <input :disabled="project.archived === 1" type="date"
                                            v-model="project.start_date" required class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mt-2">
                                        <label>Fine progetto</label>
                                        <input :disabled="project.archived === 1" type="date" v-model="project.end_date"
                                            required class="form-control">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mt-2">
                                        <label>Email supporto</label>
                                        <input :disabled="project.archived === 1" type="email"
                                            v-model="project.cs_email" async="" class="form-control">
                                        <small>Questo indirizzo verrà mostrato nella pagina contatti</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mt-2">
                                        <label>Telefono supporto</label>
                                        <input :disabled="project.archived === 1" type="tel" v-model="project.cs_phone"
                                            class="form-control">
                                        <small>Questo numero verrà mostrato nella pagina contatti</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mt-2">
                                        <label>Orari</label>
                                        <input :disabled="project.archived === 1" type="text"
                                            placeholder="Es.: Dal lunedì al venerdì dalle 9 alle 18" maxlength="200"
                                            v-model="project.cs_hours" class="form-control">
                                        <small>Lasciare vuoto per nascondere gli orari</small>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-auto">

                                </div>
                                <div class="col text-end d-flex" style="justify-content: flex-end; gap: .5rem">
                                    <!-- <button v-if="project.archived==0" type="button" @click="archiveProject(true)" class="btn btn-outline-danger"><i class="bi bi-lock"></i> Archivia progetto</button>

                                <button v-if="!project.n_iscrizioni > 0" type="button" @click="deleteProject" class="btn btn-outline-danger"><i class="bi bi-trash"></i> Elimina progetto</button> -->

                                    <button v-if="project.archived === 0" type="submit" class="btn btn-primary"><i
                                            class="bi bi-save"></i> Salva</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        </div>

        <div class="row section">
            <div class="col-12 col-lg-4 col-xl-3">
                <h4>Dominio</h4>
            </div>

            <div class="col-12 col-lg-8 col-xl-9">
                <div class="card ">
                    <div class="card-body">

                        <a :href="'https://' + project.domain" target="_blank">{{ project.domain }}</a>
                    </div>
                    <div class="card-footer">
                        Non sei autorizzato a modificare il dominio
                    </div>
                </div>
            </div>

        </div>

        <div class="row section">
            <div class="col-12 col-lg-4 col-xl-3">
                <h4>Tema</h4>
            </div>

            <div class="col-12 col-lg-8 col-xl-9">
                <GestioneTema v-if="project.projectID"  :project="project" />
            </div>

        </div>

        <div class="row section">
            <div class="col-12 col-lg-4 col-xl-3">
                <h4>Gestione slider homepage</h4>
            </div>

            <div class="col-12 col-lg-8 col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <SliderHP v-if="project.projectID" :project="project"></SliderHP>

                    </div>
                </div>
            </div>

        </div>

        <div class="row section">
            <div class="col-12 col-lg-4 col-xl-3">
                <h4>Contenuti visibili</h4>
            </div>

            <div class="col-12 col-lg-8 col-xl-9">
                <GestioneContenuti v-if="project.projectID" :project="project" />
            </div>

        </div>

        <!-- <div class="row">
            <div class="col-12 col-lg-4 col-xl-3">
                <h4>Informazioni sul progetto</h4>
            </div>

            <div class="col-12 col-lg-8 col-xl-9">
               
            </div>

        </div> -->









    </div>


</template>
<style lang="scss" scoped>
.row.section {
    
    h4 {
        font-size: 18px;
    }

    &:not(:first-child) {
        border-top: 1px solid rgba(0,0,0,.2);

    }

    padding: 1rem 0;
}

@media screen and (min-width: 900px) {
    .row.section h4 {
        text-align: right;
    }
}
</style>