<script setup>
import {ref} from 'vue';
import { request } from '@/utils/request';

const props = defineProps(["data", "permalink"])
const qs = props.data.questions;

const emit = defineEmits(["success"])

const visibleQ = ref(0);
const started = ref(false);
const results = ref(false);

function showQ(i) {
    visibleQ.value = i;
}

const answers = ref([])

for (let i=0; i<qs.length; i++) {
    answers.value.push(null)
}

function send() {
    request({
        task : "quiz/getResults",
        data : {
            responseID : props.data.responseID,
            answers : answers.value,
        },
        callback : function(dt) {
            visibleQ.value = null
            results.value = dt;

            if (props.data.threshold !== null && qs.length-dt.errors >= props.data.threshold) {
               emit("success")
            }
        }
    })
}

function icon(d,r) {

    const indice_corretto = results.value.correct[d];
    const indice_utente = answers.value[d]

    if (r == indice_corretto) {
        if (r == indice_utente) {
            return "task_alt"
        } else {
            return "check_circle"
        }
    } else {
        if (r == indice_utente) {
            return "cancel"
        } else {
            return "radio_button_unchecked"
        }
    }

}

function startAgain() {
    request({
        task : "content/getMedia",
        data : {
            permalink : props.permalink,
        },
        callback : function(dt) {
            started.value = false
            visibleQ.value = 0
            answers.value = [];
            for (let i=0; i<qs.length; i++) {
                answers.value.push(null)
            }
            results.value = false;

            props.data = dt.quiz_data
        }
    })
}

</script>
<template>
    <div class="quiz">

        <div v-if="started">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li v-for="(q,i) in qs" :key="i" class="page-item" :class="{'active' : i==visibleQ}"><a class="page-link" @click="showQ(i)">{{ i+1 }}</a>
                    </li>
                </ul>
            </nav>
            <div class="question" v-for="(q,i) in qs" :key="i" :class="{'d-none' : i!==visibleQ}">
                <div class="title">{{ q.question }}</div>
                <div class="ans">
                    <template v-if="results===false">
                        <div class="form-check" v-for="(ans,j) in q.answers" :key="j">
                            <input class="form-check-input" :name="i" v-model="answers[i]" :value="j" type="radio" :id="i+'-'+j">
                            <label class="form-check-label" :for="i+'-'+j">
                               {{ ans }}
                            </label>
                        </div>
                    </template>
                    <template v-else>
                        <div class="d-flex"  v-for="(ans,j) in q.answers" :key="j">

                            <span class="material-symbols-outlined">{{ icon(i,j) }}</span>
                            
                            {{ ans }}

                        </div>
                    </template>
                </div>
                <template v-if="results === false">
                    <div class="arrows mt-3">
                        <button class="btn btn-sm btn-outline-secondary" :disabled="i==0" @click="showQ(i-1)">Indietro</button>
                        <button class="btn btn-sm btn-outline-secondary" v-if="i!==qs.length-1" @click="showQ(i+1)">Avanti</button>
                        <button v-else class="btn btn-sm btn-success" @click="send()">Completa il quiz</button>
                    </div>
                </template>
                <template v-else>
                    <div class="arrows mt-3">
                        <button class="btn btn-sm btn-outline-secondary" @click="() => visibleQ = null">Riepilogo risultati</button>
                        <button class="btn btn-sm btn-success" @click="startAgain" >Ripeti il quiz</button>
                    </div>
                </template>
            </div>
            <div v-if="visibleQ == null && results !== false">
                <div class="row text-center results">
                    <div class="col-6">
                        <h6>Risposte esatte</h6>
                        <h2>{{ (qs.length-results.errors) }} su {{ qs.length }}</h2>
                    </div>

                    <div class="col-6">
                        <h6>Risultato</h6>
                        <h2>{{ ((((qs.length-results.errors)/qs.length)*1000)/10).toLocaleString('it-IT',{maximumSignificantDigits: 3}) }}%</h2>
                    </div>

                    <div class="col-12 my-3" v-if="props.data.threshold !== null">
                        <template v-if="qs.length-results.errors >= props.data.threshold">
                            <p>Complimenti: hai superato il test!</p>

                            <button v-if="results.errors>0" class="btn btn-sm btn-link" @click="startAgain">Voglio migliorare il mio risultato</button>
                        </template>
                        <template v-else>
                            <p><small>Per superare il test è necessario rispondere correttamente<br>ad almeno <b>{{ props.data.threshold }} domande su {{ qs.length }}</b></small></p>

                            <button class="btn btn-sm btn-success" @click="startAgain">Ripeti il quiz</button>
                        </template>
                        
                    </div>
                    <div class="col-12 my-3" v-else>
                        <button class="btn btn-sm btn-success" @click="startAgain">Ripeti il quiz</button>
                    </div>
                    

            
                </div>


               

            </div>
        </div>
        <div v-else class="start-quiz">
            <button class="btn btn-outline-primary" @click="() => started=true">Avvia il quiz</button>
        </div>

        
    </div>

</template>
<style scoped lang="scss">
.quiz {
    border: 1px solid rgba(0,0,0,.15);
    padding: 1rem;
    aspect-ratio: 16/9;
    position: relative;

    * {
        user-select: none;
    }
    
    .pagination {
        * {
            border-radius: 0;
            cursor: pointer;
        }
        
        .page-link {
            padding: 0.15rem 0.45rem
        }

        a {
            color: var(--bs-secondary)
        }

        .active a {
            color: white;
            background: var(--bs-secondary);
            border-color: var(--bs-secondary);
        }

    }
    
    .start-quiz {
        display: grid;
        place-items: center;
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
    }

    .title {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .ans {
        font-size: 13px;
    }

    .arrows {
        button:first-child {
            margin-right: .5rem;
        }
    }

    .results {
        h6 {
            color: var(--bs-secondary);
            text-transform: uppercase;
            font-weight: 300;
            font-size: 14px;
            margin: 0;
        }

        h2 {
            font-weight: 900;
        }
    }
}
</style>