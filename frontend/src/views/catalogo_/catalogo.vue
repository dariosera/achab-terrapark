<script setup>
 import Anteprima from '@/components/Anteprima.vue';
 import anteprimaCasuale from '@/fake_data/anteprimaCasuale';

</script>
<template>
    <div class="container">
        
        <section class="mt-3">
            <h1>Catalogo</h1>
            <div class="filtri">
                <div class="heading">Filtri</div>
                <div class="filtri-inner">
                    <div class="form-check corsi">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            Corsi
                        </label>
                    </div>
                    <div class="spacer"></div>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown"  data-bs-auto-close="outside"
                            aria-expanded="false">
                            Tema
                        </button>
                        <div class="dropdown-menu">
                            <div v-for="i in [1,2,3,4,5]" :key="i" class="form-check">
                                <input class="form-check-input" type="checkbox" value="" :id="'tema-'+i">
                                <label class="form-check-label" :for="'tema-'+i">
                                    Opzione {{ i }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                            aria-expanded="false">
                            Argomento
                        </button>
                        <div class="dropdown-menu">
                            <div v-for="i in [1,2,3,4,5]" :key="i" class="form-check">
                                <input class="form-check-input" type="checkbox" value="" :id="'argomento-'+i">
                                <label class="form-check-label" :for="'argomento-'+i">
                                    Opzione {{ i }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                            aria-expanded="false">
                            Durata
                        </button>
                        <div class="dropdown-menu">
                            <div v-for="i in [1,2,3,4,5]" :key="i" class="form-check">
                                <input class="form-check-input" type="checkbox" value="" :id="'durata-'+i">
                                <label class="form-check-label" :id="'durata-'+i">
                                    Opzione {{ i }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                            aria-expanded="false">
                            Tipologia
                        </button>
                        <div class="dropdown-menu">
                            <div v-for="i in [1,2,3,4,5]" :key="i" class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Opzione {{ i }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                            aria-expanded="false">
                            Lingua
                        </button>
                        <div class="dropdown-menu">
                            <div v-for="i in [1,2,3,4,5]" :key="i" class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Opzione {{ i }}
                                </label>
                            </div>
                        </div>
                    </div>
            
                </div>
            </div>
            <div class="grid-anteprime p-3">
                        <Anteprima v-for="j in Array.from({length: Math.floor(Math.random()*25+5)})" :key="j" :data="anteprimaCasuale()" />
            
                    </div>
        </section>

    </div>
</template>
<style lang="scss" scoped>

.filtri {
    padding: .5rem 0;

    .heading {
        font-size: 16px;
        font-weight: bold;
    }

    label {
        color: var(--bs-body-color);
    }

    .filtri-inner {
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
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
    margin: -1rem;
}
</style>