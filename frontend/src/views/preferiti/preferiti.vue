<script setup>
import {ref, watch} from 'vue'
import {useRoute} from 'vue-router';
import { request } from '@/utils/request';
import Dettaglio from '@/components/Dettaglio.vue';

const route = useRoute()

const tipoPreferiti = ref(route.params.tipo);
watch(
  () => route.params.tipo,
  (newId, oldId) => {
    tipoPreferiti.value = newId
    fetch()
  }
)

const titolo = {
  'corsi' : 'I tuoi corsi preferiti',
  'contenuti' : 'I tuoi contenuti preferiti'
}

const favorites = ref([])

function fetch() {
  request({
  task : "contents/favorites",
  data : {
    type: tipoPreferiti.value,
  }, callback : function(dt) {
    favorites.value = dt;
  }
})
}

fetch()

function open(i) {
  favorites.value.forEach(c => c.open = false)
  favorites.value[i].open = true;
}

function close(i) {
  favorites.value[i].open = false;
}

function removeFromFavorites(i) {
  request({
        task : `userActions/removeFromFavorites`,
        data : {
            permalink: favorites.value[i].permalink
        },
        callback : function() {
            favorites.value.splice(i,1)
        }
    })
}
</script>
<template>
  <div class="container">

    <section class="mt-3">

      <h1>{{ titolo[tipoPreferiti] }}</h1>

      <div class="lista mt-5">

        <div v-for="(c,i) in favorites" :key="i">
          <div  class="contenuto">
            <div class="image"  @click="open(i)">
              <img :src="c.image">
            </div>
            <div class="info">
              <div class="row">
                <div class="col">
                  <div class="titolo"  @click="open(i)">{{ c.title }}</div>
                </div>
                <div class="col-auto d-flex">
          
                  <div class="dropdown">
                    <button class="btn btn-link text-dark" data-bs-toggle="dropdown">
                      <span class="material-symbols-outlined">more_vert</span>
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#" @click="removeFromFavorites(i)"><span class="material-symbols-outlined">delete</span> Rimuovi</a></li>
                      <li><a class="dropdown-item" href="#"><span class="material-symbols-outlined">ios_share</span> Condividi</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="descrizione"  @click="open(i)">{{ c.description.split("|")[0] }}</div>
            </div>
          
          </div>
          <Dettaglio v-if="c.open" :data="c" @close="close(i)" :showRelated="false" />
        </div>

      </div>



    </section>


  </div>


</template>




<style scoped lang="scss">

.container {
  max-width: 1000px;
  margin: 0 auto;

}
.contenuto {
  cursor: pointer;
  display: flex;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 20px;

 

  .image {
    img {
      width: 200px;
    }
  }

  .info {
    flex: 1;
  }

  .titolo {
    font-weight: bold;
  }
  .descrizione {
    font-size: .9rem;
  }
}
</style>