<script setup>
 import Anteprima from '@/components/Anteprima.vue';
 import SkeletonAnteprima from '@/components/SkeletonAnteprima.vue';
 import Dettaglio from '@/components/Dettaglio.vue';
import { reactive, onMounted , ref, watch} from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTerraParkStore } from '@/stores/commons';
import { request } from '@/utils/request';

const tps = useTerraParkStore();

const route = useRoute()
const router = useRouter()

const contenuti = ref([])
const fetching = ref(true);
const nSkeletons = ref(20);

const fetch = () => {
    fetching.value = true
    request({
        task: "contents/search",
        data: {},
        callback: (dt) => {

            const corsi = dt.filter(x => x.isCourse);
            const altro = dt.filter(x => !x.isCourse);

            
            altro.sort((a,b) => {
                return Math.random() - 0.5;
            })

            contenuti.value = [...corsi, ...altro]

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

            filters._update = (new Date()).getTime()
            
        }
    })
}

fetch()

const temi = ref([])
const topics = ref(tps.getTopics())
const typologies = ref(tps.getTypologies())
const languages = ref(tps.getLanguages())

onMounted(async () => {
    // if ("undefined" !== typeof(route.params.id)) {
    //     selezionaTema(route.params.id)
    // }

    if (route.query) {
        let filtersFromUrl = deserializeFilters(route.query)

        for (let ft in filtersFromUrl) {
            filters[ft] = filtersFromUrl[ft]
        }
    }

    tps.getThemes().then(t => temi.value = t)
})



const filters = reactive({
    themes : [],
    topics : [],
    typologies : [],
    languages: [],
    onlyCourses : false
 })

 watch(filters, () => {
    console.log(`Update filters`)

    const serializedFilters = serializeFilters(filters)
    router.push(`/catalogo?${serializedFilters}`)

    contenuti.value.forEach(c => {

        // Reset
        if (filters.topics.length === 0 && filters.typologies.length === 0 && filters.languages.length === 0 && filters.themes.length === 0) {
            c.isVisible = true;
        } else {

            c.isVisible = true;

            // Argomento
            if (filters.topics.length > 0) {
                if (filters.topics.includes(c.topicID)) {
                    //c.isVisible = true
                } else {
                    c.isVisible = false
                }
            }

            // Tipologia
            if (filters.typologies.length > 0) {
                if (filters.typologies.includes(c.typologyID)) {
                    //c.isVisible = true
                } else {
                    c.isVisible = false
                }
            }

            // Lingua
            if (filters.languages.length > 0) {
                if (filters.languages.includes(c.language)) {
                    //c.isVisible = true
                } else {
                    c.isVisible = false
                }
            }

            // Tema
            if (filters.themes.length > 0) {
                if (filters.themes.includes(c.themeID)) {
                    //c.isVisible = true
                } else {
                    c.isVisible = false
                }
            }

        }
        

        // Filtro corsi, sui rimanenti
        if (filters.onlyCourses === true && c.isVisible) {
            c.isVisible = c.isCourse
        }



    })



 } , { deep: true});

 const toggleTema = function(i) {
    if (filters.themes.includes(i)) {
        filters.themes = filters.themes.filter(a => a != i);
    } else {
        filters.themes.push(i);
    }
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


 const  serializeFilters = (filters) => {
  const params = new URLSearchParams();

  for (const key in filters) {
    if (key === "_update") continue;
    if (filters.hasOwnProperty(key)) {
      const value = filters[key];

      if (Array.isArray(value)) {
        if (value.length > 0) {
          params.append(key, value.join(','));
        }
      } else if (value !== false) {
        params.append(key, value);
      }
    }
  }

  return params.toString();
}

function deserializeFilters(queryString) {
  const params = new URLSearchParams(queryString);
  const filters = {};

  for (const [key, value] of params.entries()) {
    if (value === 'true' || value === 'false') {
      filters[key] = value === 'true';
    } else {
      filters[key] = value.split(',').map(item => isNaN(item) ? item : Number(item));
    }
  }

  return filters;
}
</script>
<template>
    <div class="container">

        <section class="mt-3">

            <h1>{{ $t('common.catalogo') }}</h1>

            <div class="grid-temi" v-if="temi.length > 1">
                <a v-for="tema in temi" :key="tema.themeID" class="tema" :class="{selected : filters.themes.includes(tema.themeID)}" @click="toggleTema(tema.themeID)">
                    <div class="icona">
                        <img :src="tema.url_img">
                    </div>
                    <h3>{{ tema.title }}</h3>
                </a>
            </div>
        </section>


        <div class="filters">

            <div class="filters-inner">

                <div class="form-check corsi">
                    <input class="form-check-input" type="checkbox" v-model="filters.onlyCourses" id="checkbox-courses">
                    <label class="form-check-label text-dark" for="checkbox-courses">
                        {{ $t('common.corsi') }}
                    </label>
                </div>

                <div class="spacer"></div>

                <!-- <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        {{ $t('common.temi') }}
                    </button>
                    <div class="dropdown-menu">
                        <div v-for="i in [1,2,3,4,5]" :key="i" class="form-check">
                            <input class="form-check-input" type="checkbox" value="" :id="'tema-'+i">
                            <label class="form-check-label" :for="'tema-'+i">
                                Opzione {{ i }}
                            </label>
                        </div>
                    </div>
                </div> -->

                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        <span v-if="filters.topics.length > 0" class="material-symbols-outlined filter-icon">filter_alt</span>
                        {{ $t('common.argomento') }}
                    </button>
                    <div class="dropdown-menu">
                        <div v-for="(v,i) in topics" :key="i" class="form-check">
                            <input class="form-check-input" type="checkbox" :value="v.topicID" v-model="filters.topics" :id="'argomento-'+v.topicID">
                            <label class="form-check-label" :for="'argomento-'+v.topicID">
                                {{ v.title }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        {{ $t('common.durata') }}
                    </button>
                    <div class="dropdown-menu">
                        <div v-for="i in [1,2,3,4,5]" :key="i" class="form-check">
                            <input class="form-check-input" type="checkbox" value="" :id="'durata-'+i">
                            <label class="form-check-label" :id="'durata-'+i">
                                Opzione {{ i }}
                            </label>
                        </div>
                    </div>
                </div> -->

                <!-- <span class="bg-warning">--- FILTRO DURATA ? ---</span> -->

                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        <span v-if="filters.typologies.length > 0" class="material-symbols-outlined filter-icon">filter_alt</span>
                        {{ $t('common.tipologia') }}
                    </button>
                    <div class="dropdown-menu">
                        <div v-for="(v,i) in typologies" :key="i" class="form-check">
                            <input class="form-check-input" type="checkbox" :value="v.typologyID" v-model="filters.typologies" :id="`typology-${v.typologyID}`">
                            <label class="form-check-label" :for="`typology-${v.typologyID}`">
                               {{ v.description }}
                            </label>
                        </div>
                    </div>
                </div>

                <div v-if="false" class="dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                        aria-expanded="false">
                        <span v-if="filters.languages.length > 0" class="material-symbols-outlined filter-icon">filter_alt</span>
                        {{ $t('common.lingua') }}
                    </button>
                    <div class="dropdown-menu">
                        <div v-for="(v,i) in languages" :key="i" class="form-check">
                            <input class="form-check-input" type="checkbox" :value="v.iso_639_1" v-model="filters.languages" :id="`language-${v.iso_639_1}`">
                            <label class="form-check-label" :id="`language-${v.iso_639_1}`">
                                {{ v.description }}
                            </label>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <div v-if="fetching === true" class="grid-anteprime p-3">
            <div v-for="j in nSkeletons" :key="j" class="single">
                    <SkeletonAnteprima />
            </div>
        </div>
        <div v-else class="grid-anteprime p-3">
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

    </div>
</template>
<style lang="scss" scoped>
.grid-temi {
    display: flex;
    gap: 1rem;
    

    .tema {
        width: 200px;
        color: var(--bs-body-color);
        text-decoration: none;
        cursor: pointer;

       
        .icona {
            width: 100%;
            aspect-ratio: 1;
            background-color: rgba(var(--bs-body-color-rgb), .05);
            background-size: 80% auto;
            background-position: center center;
            background-repeat: no-repeat;
            transition: background-color .3s ease-in-out;
            border: 1px solid rgba(var(--bs-body-color-rgb), .01);
            padding: 1rem;

            img {
                display: block;
                width: 100%;
            }

            &:hover {
                background-color: rgba(var(--bs-body-color-rgb), .1);
                border-color: rgba(var(--bs-body-color-rgb), .01);
            }

           
        }

        h3 {
            font-size: .95rem;
            margin-top: .5rem;
            font-weight: bold;
            transition:all .2s ease-in-out;
            color: rgba(0,0,0,.7);
        }

        &.selected h3 {
            color: #000;
        }


       

        &.selected .icona {
            background-color: rgba(var(--bs-body-color-rgb), .15);
            border-color: rgba(var(--bs-body-color-rgb),.5);
        }
    }

   
    
}


.filters {
    padding: .5rem 0;

    .heading {
        font-size: 16px;
        font-weight: bold;
    }

    .filter-icon {
        vertical-align: bottom;
    }

    .btn:has(.filter-icon) {
        font-weight: bold;
        color: var(--bs-primary)!important;
    }

    .filters-inner {
        display: flex;

        color: rgba(var(--bs-body-color-rgb), .5);
        align-items: center;
        height: 3rem;

        .form-check {
            margin-right: 1rem;
        }

        input[type='checkbox'] {
            border-radius: 0;
            border-width: 1px;
            border-color: rgba(var(--bs-body-color-rgb), .5)
        }

        .spacer {
            border-right: 1px solid black;
            height: 1.5rem;
        }

        .dropdown .btn-link {
            color: var(--bs-body-color);
            text-decoration: none;
            
        }

        .dropdown-menu {
            padding: 1rem;
            border-radius: 0;
        }
    }


}

.grid-anteprime {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin: -1rem;
    grid-auto-flow: dense;

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

@media screen and (max-width: 768px) {
    .grid-temi .tema .icona {
        width: 60px;
        padding: 6px;
    }
}
</style>