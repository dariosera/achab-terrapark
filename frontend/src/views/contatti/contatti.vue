<script setup>

import { useProjectStore } from '@/stores/project';
import {ref} from 'vue'
import { request } from '@/utils/request';

const ps = useProjectStore()
const text = ref("");

const sendMessage = () => {
    request({
        task : "contacts/customerCare",
        data : {
            text
        },
        callback: () => {
            text.value = ""
        }
    })
}


</script>
<template>
    <div class="container">
        <section class="mt-3">
            <h1>Contatti e assistenza</h1>

            <div class="row mt-3">
                <div class="col-lg-6 order-lg-last">

                    <div class="card">
                        <div class="card-body">
                            <h5>Progetto cliente</h5>

                            <p>Il tuo accesso a <b>{{ ps.getTitle() }}</b> è offerto da <b>{{
                                    ps.getCustomer()}}</b>
                            </p>

                        </div>
                    </div>


                    <div class="card mt-3" v-if="ps.getContacts().phone">
                        <div class="card-body">
                            <h5><span class="material-symbols-outlined">
                                    support_agent
                                </span> Via telefono</h5>

                            <div class="big">{{ ps.getContacts().phone }}</div>

                            <small v-if="ps.getContacts().hours">{{ ps.getContacts().hours }}</small>
                        </div>
                    </div>

                    <div class="card mt-3" v-if="ps.getContacts().email">
                        <div class="card-body">
                            <h5><span class="material-symbols-outlined">
                                    forward_to_inbox
                                </span> Via email</h5>

                            <div class="big">{{ ps.getContacts().email }}</div>
                        </div>
                    </div>


                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">

                            <h5>Hai bisogno di aiuto?<br>
                                Siamo qui per te!</h5>

                            <form @submit.prevent="sendMessage">
                                <div class="mb-3">
                                    <textarea rows="4" class="form-control mt-3"
                                        :placeholder="$t('contatti.messaggio')+'...'" v-model="text" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="privacy" required>
                                        <label class="form-check-label" for="privacy">
                                            <small>{{ $t('contatti.privacy') }} <a href="#">Privacy Policy</a>.</small>
                                        </label>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-outline-primary">{{ $t('contatti.inviaMessaggio')
                                    }}</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<style scoped lang="scss">
.big {
    font-size: 22px;
    font-weight: bold;
    color: rgba(var(--bs-body-color-rgb),.6);
}
</style>