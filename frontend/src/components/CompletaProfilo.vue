<script setup>
import { reactive, ref } from 'vue'
import { request } from '@/utils/request';

const props = defineProps(["modal"])

const step = ref(1);

const comuni = ref([])

const dati = reactive({
    anno_nascita: '',
    cerca_comune: '',
    comune: null,
    professione: null,
    indirizzo : null,
    codice_fiscale : null,
    telefono : null,
})

const cercaComuni = () => {
    request({
        task: "profile/cercaComune",
        data: {
            s: dati.cerca_comune,
        },
        callback: function (dt) {
            comuni.value = dt;
        }
    })
}

const impostaComune = () => {
    const find = comuni.value.filter(x => x.nome == dati.cerca_comune)

    if (find.length === 1) {
        dati.comune = find[0].codice_catastale
    } else {
        dati.comune = null;
    }

}

function submit() {
    
    if (step.value == 1) {
        step.value = 2;
        return;
    }

    request({
        task : "profile/updateMandatory",
        data : dati,
        callback : function(dt) {
            props.modal.hide();
        }
    })
}

</script>
<template>
    <form @submit.prevent="submit">
        <div class="row">
            <template v-if="step == 1">
                <div class="col-6">
                    <label>Anno di nascita *</label>
                    <input type="number" required v-model="dati.anno_nascita" class="form-control">
                </div>
                <div class="col-6">
                    <label>Comune di residenza * </label>
                    <input type="text" required class="form-control" @change="impostaComune" @keyup="cercaComuni" id="citta"
                        v-model="dati.cerca_comune" list="data" autocomplete="off">
                    <datalist id="data">
                        <option v-for="(c, i) in comuni" :key="i" :value="c.nome"></option>
                    </datalist>
                    <small v-if="dati.comune" class="d-block mt-2"><span class="material-symbols-outlined"
                            style="vertical-align: middle;">verified</span> Codice catastale <b>{{ dati.comune }}</b></small>
                    <small v-else class="d-block mt-2 text-danger">Comune non trovato</small>
                </div>
                <div class="col-12"><small>(*) Dati obbligatori</small></div>
            </template>
            <template v-if="step == 2">
                <p>Inserisci anche dati facoltativi per migliorare il dialogo con le iniziative territoriali.</p>

                <div class="mb-3">
                  <label for="telefono" class="form-label">Telefono</label>
                  <input type="tel" class="form-control" id="telefono" v-model="dati.telefono">
                </div>

                <div class="mb-3">
                  <label for="professione" class="form-label">Professione</label>
                  <input type="text" class="form-control" id="professione" v-model="dati.professione">
                </div>
                <div class="mb-3">
                  <label for="indirizzo" class="form-label">Indirizzo di residenza</label>
                  <textarea class="form-control"  id="indirizzo" v-model="dati.indirizzo"></textarea>
                </div>
                <div class="mb-3">
                  <label for="codice_fiscale" class="form-label">Codice Fiscale</label>
                  <input type="text" class="form-control" id="codice_fiscale" v-model="dati.codice_fiscale" >
                </div>
            </template>
            <div class="col-12 mt-3 text-end">
                <button class="btn btn-outline-primary me-2" v-if="step==2">Continua senza i dati facoltativi</button>
                <button class="btn btn-primary">{{ step == 1 ? 'Prosegui' : 'Salva' }}</button>
            </div>
        </div>
    </form>



</template>