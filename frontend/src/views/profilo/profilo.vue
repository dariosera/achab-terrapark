<script setup>
import { request } from '@/utils/request';
import {reactive, ref, onMounted} from 'vue'
import QRCode from 'qrcode'
import useModals from '@/stores/modals';

const profile = reactive({})
const extra = reactive({})
const qrcodecanvas = ref(null)



request({
  task : "profile/get",
  callback : dt => {
    Object.assign(profile, dt)
    QRCode.toCanvas(qrcodecanvas.value, `https://users.terrapark.it/${btoa(profile.profile_signature)}`)

  }
})

const aggiornaDatiPersonali = () => {
  request({
    task : "profile/update",
    data : profile,
    callback: () => {

    }
  })
}

const comuni = ref([])

const cercaComuni = () => {
  request({
    task : "profile/cercaComune",
    data : {
      s : profile.cerca_comune,
    },
    callback: function(dt) {
      comuni.value = dt;
    }
  })
}

const impostaComune = () => {
  const find = comuni.value.filter(x => x.nome == profile.cerca_comune)

  if (find.length === 1) {
    profile.comune = find[0].codice_catastale
  } else {
    profile.comune = null;
  }

}

function eliminaAccount() {
  useModals().msgbox({
    title : "Eliminazione Account",
    content : `<p>Per eliminare l'account, contatta l'assistenza.</p>`
  })
}
</script>
<template>
  <div class="container">

    <section class="mt-3">

      <h1>Profilo</h1>

      <div class="row">
        <div class="col-lg-4">
          <!-- Sezione Dati Personali -->
          <div class="card mb-4">
            <h5 class="card-header">
              {{ $t('profilo.datiPersonali') }}
            </h5>
            <div class="card-body">
              <form @submit.prevent="aggiornaDatiPersonali">
                <div class="mb-3">
                  <label for="name" class="form-label">{{ $t('profilo.nome') }}</label>
                  <input type="text" class="form-control" id="name" v-model="profile.nome" required>
                </div>
                <div class="mb-3">
                  <label for="cognome" class="form-label">{{ $t('profilo.cognome') }}</label>
                  <input type="text" class="form-control" id="cognome" v-model="profile.cognome" required>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">{{ $t('profilo.email') }}</label>
                  <input type="email" disabled class="form-control" id="email" v-model="profile.email" required>
                </div>
                <div class="mb-3">
                  <label for="telefono" class="form-label">{{ $t('profilo.telefono') }}</label>
                  <input type="tel" class="form-control" id="telefono" v-model="profile.telefono">
                </div>
                <div class="mb-3">
                  <label for="citta" class="form-label">{{ $t('profilo.citta') }}</label>
                  <input type="text" class="form-control" @change="impostaComune" @keyup="cercaComuni" id="citta" v-model="profile.cerca_comune" list="data" required autocomplete="off">

                  <datalist id="data">
                      <option v-for="(c,i) in comuni" :key="i" :value="c.nome"></option>
                  </datalist>

                  <small v-if="profile.comune" class="d-block mt-2"><span class="material-symbols-outlined" style="vertical-align: middle;">verified</span> Codice catastale <b>{{ profile.comune }}</b></small>
                  <small v-else class="d-block mt-2 text-danger">Comune non trovato</small>
                </div>
                <div class="mb-3">
                  <label for="anno_nascita" class="form-label">{{ $t('profilo.anno_nascita') }}</label>
                  <input type="number" class="form-control" id="anno_nascita" v-model="profile.anno_nascita" required>
                </div>
                <button type="submit" class="btn btn-outline-primary">Salva</button>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <!-- Sezione Dati Personali -->
          <div class="card mb-4">
            <h5 class="card-header">
              Informazioni aggiuntive 

            </h5>
            <div class="card-body">

              <p>Completa il tuo profilo e per migliorare il dialogo con le iniziative territoriali.
</p>

              <form @submit.prevent="aggiornaDatiPersonali">
                <div class="mb-3">
                  <label for="name" class="form-label">Professione</label>
                  <input type="text" class="form-control" id="name" v-model="profile.professione">
                </div>
                <div class="mb-3">
                  <label for="cognome" class="form-label">Indirizzo di residenza</label>
                  <textarea class="form-control" v-model="profile.indirizzo"></textarea>
                </div>
                <div class="mb-3">
                  <label for="text" class="form-label">Codice Fiscale</label>
                  <input type="text" class="form-control" id="email" v-model="profile.codice_fiscale" >
                </div>
               
                <button type="submit" class="btn btn-outline-primary">Salva</button>
              </form>
            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card mb-4">
      <h5 class="card-header">
        {{ $t('profilo.cambioPassword') }}
      </h5>
      <div class="card-body">
        <form v-if="!profile.sso" @submit.prevent="cambiaPassword">
          <div class="mb-3">
            <label for="currentPassword" class="form-label">{{ $t('profilo.passwordAttuale') }}</label>
            <input type="password" class="form-control" id="currentPassword" v-model="profile.currentPassword" required>
          </div>
          <div class="mb-3">
            <label for="newPassword" class="form-label">{{ $t('profilo.nuovaPassword') }}</label>
            <input type="password" class="form-control" id="newPassword" v-model="profile.newPassword" required>
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">{{ $t('profilo.confermaNuovaPassword') }}</label>
            <input type="password" class="form-control" id="confirmPassword" v-model="profile.confirmPassword" required>
          </div>
          <button type="submit" class="btn btn-outline-primary">{{ $t('profilo.cambiaPassword') }}</button>
        </form>
        <div v-else>
          <p>Il tuo profilo utilizza l'autenticazione SSO (Single Sign-On) tramite Google.</p>
        </div>
      </div>
    </div>

    <!-- Sezione Elimina Account -->
    <div class="card mb-4">
      <h5 class="card-header">
        {{ $t('profilo.eliminaAccount') }}
      </h5>
      <div class="card-body">
        <form @submit.prevent="eliminaAccount">
          <p>{{ $t('profilo.avvertimentoEliminaAccount') }}</p>
          <button type="submit" class="btn btn-outline-danger">{{ $t('profilo.eliminaAccount') }}</button>
        </form>
      </div>
    </div>


     <div class="card mb-4">
      <h5 class="card-header">
        Il tuo qrcode
      </h5>
      <div class="card-body">
        <canvas ref="qrcodecanvas"></canvas>
      </div>
    </div>

        </div>



      </div>

    </section>



    <!-- Sezione Cambio Password -->
    
  </div>
</template>
<style scoped>
canvas {
  width: 100%;
  
}
</style>