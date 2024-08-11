<script setup>
import {request, Modal, useToasts} from 'kadro-core'
import { onMounted, ref, reactive } from 'vue';



const clienti = ref([]);

onMounted(() => {

    getClienti()


})

const getClienti = () => {
    request({
        task : "customers/get",
        callback : function(dt) {
            clienti.value = dt;
        }
    });
}

const nuovoCliente = reactive({
    visible: false,
    show : function() { nuovoCliente.bsmodal.show()},
    nome : "",
    ready : (modal) => {
        nuovoCliente.bsmodal = modal;
    },
    bsmodal : {},
    actions : [
        {
            text : "Aggiungi",
            class : "btn-primary",
            callback : function() {
                request({
                    task : "customer/add",
                    data : nuovoCliente,
                    callback : function() {
                        useToasts().addToast({
                            title : "",
                            content : `Il cliente <b>${nuovoCliente.nome}</b> è stato aggiunto.`
                        })

                        getClienti()
                        nuovoCliente.nome = "";
                    }
                })
            }
        }
    ]
})

const eliminaCliente = function(id) {
    request({
        task : "customer/delete",
        data : { customerID : id },
        callback : function() {
            useToasts().addToast({
                title : "",
                content : `Il cliente è stato eliminato.`
            })

            getClienti()
        }
    })
}

</script>
<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h5 class="card-title">Clienti</h5>
                    </div>
                    <div class="col text-end">
                        <button class="btn btn-sm btn-outline-primary" @click="nuovoCliente.show()"><i class="bi bi-plus"></i> Nuovo cliente</button>
                    </div>
                </div>
               
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th colspan="3">Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="clienti.length === 0">
                            <td><span class="text-muted">Nessun cliente</span></td>
                        </tr>
                        <tr v-for="item in clienti">
                            <td :title="'IDcliente = '+item.customerID">{{ item.customer }}</td>
                            <td class="text-end"><button class="btn btn-sm btn-link text-danger"
                                    @click="eliminaCliente(item.customerID)"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-end">
                Clienti totali: {{ clienti.length  }}
            </div>
        </div>
        <Modal title="Nuovo cliente" :actions="nuovoCliente.actions" canDismiss="true" size="md" @ready="nuovoCliente.ready">
            <div class="form-group">
                <label>Nome del cliente</label>
                <input type="text" class="form-control" v-model="nuovoCliente.customer">
            </div>
        </Modal>
    </div>
</template>
<style lang="scss" scoped>
.card {
    min-height: calc(100vh - 100px);
}
</style>