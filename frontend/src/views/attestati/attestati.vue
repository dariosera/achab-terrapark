<script setup>
import anteprimaCasuale from "@/fake_data/anteprimaCasuale"
import {ref} from 'vue'

const favorites = ref([])

for (let i=0; i<parseInt(Math.random()*10); i++)
  favorites.value.push(anteprimaCasuale())
</script>
<!-- <template>
  <div class="container">

    <section class="mt-3">

      <h1>Qualifiche e attestati</h1>

      <div class="row">
        <div class="col">
          <h3>Il tuo punteggio personale</h3>
        <p>Punteggio accumulato grazie alle attività realizzate e ai contenuti visualizzati sulla piattaforma. Continua a partecipare per aumentare il tuo punteggio!</p>
        </div>
        <div class="col-auto col-punti">
          <div class="punti">1234</div>
        </div>
        
      </div>

      <div class="lista mt-5">

        <div v-for="(c,i) in favorites" :key="i" class="contenuto">
          <div class="image">
            <img :src="c.image">
          </div>
          <div class="info">

        
          </div>

<div class="lista mt-5">

  <div v-for="(c,i) in favorites" :key="i" class="contenuto">
    <div class="image">
      <img :src="c.image">
    </div>
    <div class="info">

      <div class="row">
            <div class="row">
              <div class="col">
                <div class="titolo">{{ c.title }}</div>
              </div>
              <div class="col-auto d-flex">
                <button class="btn btn-link text-dark">
                  <span class="material-symbols-outlined">close</span>
                </button>

                <div class="dropdown">
                  <button class="btn btn-link text-dark" data-bs-toggle="dropdown">
                    <span class="material-symbols-outlined">more_vert</span>
                  </button>

                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Vai al corso</a></li>
                    <li><a class="dropdown-item" href="#">Condividi</a></li>
                  </ul>

                </div>
              </div>
            </div>


            <div class="descrizione">{{ c.description.split("|")[0] }}</div>

            <div class="download mt-3">

              <a href="#" class="btn btn-sm btn-primary">Scarica l'attestato</a>

            </div>

          </div>



        </div>

      </div>



    </section>


  </div>


</template> -->
<template>
  <div class="container">
    <section class="mt-3">
      <h1>Qualifiche e attestati</h1>
      <p>Pagina in costruzione</p>
    </section>
  </div>
</template>
<style scoped lang="scss">
</style>