<script setup>

import {ref, reactive} from 'vue'
import { request } from '@/utils/request';
import { useRoute } from 'vue-router';
import Anteprima from '@/components/Anteprima.vue';
import SkeletonAnteprima from '@/components/SkeletonAnteprima.vue';
import Dettaglio from '@/components/Dettaglio.vue';

const route = useRoute()
const temi = ref([])

request({
    task : "themes/getWithDetails",
    callback: (dt) => {
        temi.value = dt;
        temi.value.forEach((tema,i) => {
            import(`../../assets/theme_icons/${tema.url}.svg`).then(img => {
                temi.value[i].url_img = img.default;
            })
        })

        setTimeout(() => {
            if (route.hash.length > 0) {
                console.log(route.hash)
                document.querySelector(route.hash)?.scrollIntoView({behavior: 'smooth'}) 
            }
        }, 150)
       
        
    }
})

const contenuti = ref([])
const fetching = ref(true);
const nSkeletons = ref(20);

const fetch = () => {
    fetching.value = true
    request({
        task: "contents/search",
        data: {
            target : "courses"
        },
        callback: (dt) => {
            contenuti.value = dt
            contenuti.value.forEach(c => {
                c.isOpen = false
                c.isVisible = true;
            })

            let ms = 1000 / Math.abs(nSkeletons.value - contenuti.value.length);

            let interval = setInterval(() => {
                if (nSkeletons.value < contenuti.value.length) {
                    nSkeletons.value++;
                } else if (nSkeletons.value > contenuti.value.length) {
                    nSkeletons.value--;
                } else {
                    clearInterval(interval)
                    fetching.value = false
                }
            }, ms)

            //filters._update = (new Date()).getTime()
            
        }
    })
}

const open = (i) => {
    contenuti.value.forEach((c,j) => {
        if (j == i) {
            c.isOpen = true
        } else {
            c.isOpen = false
        }
    })
    window.setTimeout(() => {
        window.scrollTo({
        top: document.querySelector(".single.expand")?.offsetTop,
        behavior : 'smooth'
    })
    },50)
    
 }

fetch()



const key_topics = reactive([
    { title : 'Raccolta differenziata di qualità', content : `Una gestione efficace dei rifiuti inizia con una raccolta differenziata ben organizzata. Questo processo è cruciale per garantire che i materiali possano essere riciclati in modo efficiente e trasformati in nuove risorse, riducendo così la necessità di estrarre materie prime vergini.`},
    { title : `Prevenzione rifiuti e riuso`, content : `Ridurre alla fonte la produzione di rifiuti è una priorità assoluta. Attraverso strategie di riuso e la progettazione di prodotti durevoli, possiamo estendere il ciclo di vita degli oggetti, evitando che finiscano prematuramente in discarica.`},
    { title : `Riduzione degli sprechi e consumo consapevole`, content : `Adottare pratiche di consumo più responsabili significa scegliere prodotti realizzati in modo sostenibile, preferire la riparazione al posto della
sostituzione e ridurre lo spreco alimentare. Azioni quotidiane che possono fare una grande differenza nell’impatto ambientale complessivo.`},
    { title : `Stili di vita sostenibili e decoro urbano`, content : `Una comunità orientata all’economia circolare promuove stili di vita che rispettano l’ambiente, attraverso buone pratiche comuni, il rispetto degli spazi pubblici e il coinvolgimento attivo dei cittadini nella cura del territorio.`},
    { title : `Innovazione e crescita economica`, content : `La transizione verso un’economia circolare richiede innovazione in ogni settore, dalla produzione alla distribuzione, fino al consumo. Questo cambiamento è una spinta verso la creazione di nuovi modelli di business che valorizzano le risorse e supportano una crescita economica duratura.`},
])


</script>
<template>
    <div class="container">
        <section class="mt-5">

            <div class="row">
                <div class="d-none d-md-block col-md-4 col-lg-3">
                    <div class="icona"><img :src="temi[0].url_img"></div>
                </div>
                <div class="col-md-8 col-lg-9">

                    <div class="wrap-titolo-tema">
                        <div class="icona-tema-mobile"><img :src="temi[0].url_img"></div>
                        <h1>{{ temi[0].title }}</h1>
                    </div>

                    

                    <p>L’economia circolare rappresenta un modello di produzione e consumo che mira a ridurre al minimo i rifiuti e a valorizzare le risorse esistenti;
un’opportunità di sviluppo sostenibile, in linea con il piano d’azione europeo, fondato su innovazione, crescita economica e rispetto per l’ambiente.
Questo tema esplora strategie e pratiche per prevenire la produzione di rifiuti, estendere il ciclo di vita dei prodotti e garantire una raccolta
differenziata di alta qualità.</p>

<p>L’obiettivo principale dell’economia circolare è quello di trasformare il modello economico lineare, basato sul meccanismo “produci, usa e getta”, in
un sistema circolare in cui ogni scarto diventa una risorsa. Un paradigma che rivoluziona il modo in cui produciamo e consumiamo beni e servizi,
basato sul principio di “chiudere il cerchio” della produzione, creando un sistema in cui i prodotti e i materiali mantengono il loro valore il più a
lungo possibile.</p>

<p>Questo approccio non solo favorisce la sostenibilità ambientale, ma rappresenta anche un’opportunità per l’innovazione e la crescita economica,
    promuovendo nuovi modelli di business.</p>


                </div>
            </div>

            <div class="separator"></div>


            <div class="row">
                <div class="col-md-4 col-lg-3 text-end content-left">
                    <p>
                        Perseguire la transizione verso
                        l’economia circolare non è
                        solo una scelta etica, ma una
                        <b>
                            necessità per garantire un
                            futuro sostenibile.</b>
                    </p>

<p>
    Ogni individuo, azienda e
    istituzione può contribuire a
    questo cambiamento culturale,
    trasformando il modo in cui
    vediamo e utilizziamo le risorse.
</p>

<p>
    Il nostro impegno collettivo verso
    la riduzione dei rifiuti, l’efficienza
    energetica e l’innovazione sarà
    la chiave per costruire un mondo
    più giusto e prospero per le
    generazioni future.
</p>
                </div>

                <div class="col-md-8 col-lg-9 key-topics">

                    <h2>Argomenti chiave dell'Economia circolare:</h2>

                    <div v-for="(kt,i) in key_topics" :key="i" >
                        <h3><span class="material-symbols-outlined">arrow_forward</span> {{ kt.title }}</h3>

                        <p>{{ kt.content}}</p>
                    </div>

                </div>

            </div>

            <div class="separator"></div>

    <h2>Tutti i corsi</h2>

        <div v-if="fetching === true" class="grid-anteprime p-3">
            <div v-for="j in nSkeletons" :key="j" class="single">
                    <SkeletonAnteprima />
            </div>
        </div>
        <div v-else class="grid-anteprime p-3" :class="{'few': contenuti.length < 3}">
            <template v-for="(c,j) in contenuti" :key="j">
                <div v-if="c.isVisible" class="single" :class="{'expand' : c.isOpen}">
                        <Anteprima v-if="!c.isOpen" :data="c" @mostraDettaglio="open(j)" />
                        <Dettaglio v-else :data="c" @close="open(null)" :showRelated="false" />
                </div>
            </template>
            <template v-if="contenuti.filter(c => c.isVisible).length === 0">
                <div class="alert text-center">Nessun risultato</div>
            </template>
        </div>

        </section>
    </div>

    

</template>
<style scoped lang="scss">

h1 {
    font-size: 32px;
}

.separator {
    height: 3px;
    background-color: var(--bs-body-color);
    margin: 1rem 0;
}

.tema {
    border: 1px solid rgba(var(--bs-body-color-rgb),.15);
    padding: 2rem;
    margin-bottom: 2rem;
}

.content-left {
    font-size: 19px;
    color: var(--bs-secondary);
}

.key-topics {
    h2 {
        font-size: 26px;
        margin-bottom: 15px;
    }

    h3 {
        font-size: 22px;
        position: relative;
        padding-left: 2rem;

        span {
            position: absolute;
            top: 0;
            left: 0;
        }
    }

    p {
        line-height: 1.1;
    }
}

.icona {
    aspect-ratio: 1;
    padding: 3rem;
    img {
        height: 100%;
        aspect-ratio: 1;
        display: block;
    }

    background: rgba(0,0,0,.05);
}

.info-tema {
    align-content: center;
}

h2 {
    font-size: 20px;
    font-weight: bold;
}


.grid-anteprime {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin: -1rem;
    grid-auto-flow: dense;

    &.few {
        grid-template-columns: repeat(auto-fit, 300px);
    }

    .single {

        .anteprima {
            max-width: 400px;
        }
        
        &.expand {
            grid-column-start: 1;
            grid-column-end: -1;
        }
    }
}

.icona-tema-mobile {
    display: none;
}

@media (max-width: 768px) {

    .wrap-titolo-tema {
        display: flex;
        gap: .5rem;
        align-items: center;
    }

    h1 {
        flex: 1;
    }
    
    .icona-tema-mobile {
        display: inline-block;
        vertical-align: middle;
        padding: 5px;
        background: rgba(0,0,0,.05);
        
        img {
            width: 4rem;
            height: 4rem;
        }
    }

    h1 {
        font-size: 24px;
    }


    .content-left {
        text-align: left!important;
    }

    .key-topics {
        margin-top: 25px;

        p {
            line-height: 1.3;
        }
    }
}
</style>