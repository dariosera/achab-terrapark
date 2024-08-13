<script setup>
import {ref} from 'vue';
import {request, useModals} from 'kadro-core';

const um = useModals();

const attivaAccount = ref(false)

request({
    task : 'core.profilo.gruppi',
    callback : function(dt) {
        if (dt.length === 0) {
            attivaAccount.value = true
        }
    }
})
function verificaAccount() {

    request({
        task : "ui.attivaPermessi",
        callback : function(dt) {
            if (dt.refresh) {
                um.msgbox({
                    title : `Autorizzazioni concesse`,
                    content : `Per accedere alle funzioni del pannello, devi effettuare nuovamente il login.`,
                    canDismiss: false,
                    actions : [
                        {
                            text : `<i class="bi bi-box-arrow-in-right"></i> Vai al login`,
                            class : `btn-primary`,
                            callback : function() {
                                localStorage.removeItem("sspa_token");
                                window.location.reload();
                            }    
                        }
                    ]
                })
            }
        }
    })
}

</script>
<template>
    <div class="p-3">
        <h1>TerraPark</h1>
        <p>Benvenuto nel pannello di gestione.</p>

        <hr>

        <div v-if="attivaAccount" class="border p-3 my-3">
            <h5>Attiva il tuo account</h5>
            <div>Non sei autorizzato ad accedere alle funzioni del pannello di gestione di TerraPark.</div>
            <hr>
            <button class="btn btn-primary" @click="verificaAccount"><i class="bi bi-lock"></i> Richiedi accesso</button>
        </div>

        <div>
            <b>TerraPark</b> è un progetto di<br>
            <img src="@/assets/logo_achab.png" style="height: 2rem;">
        </div>


    </div>
</template>