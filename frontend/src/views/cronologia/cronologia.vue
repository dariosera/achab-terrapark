<script setup>
import {ref} from 'vue'
import { request } from '@/utils/request';
import { formatDistance } from 'date-fns'
import { it } from 'date-fns/locale'
import Dettaglio from '@/components/Dettaglio.vue';

const history = ref([])
request({
  task : "contents/history",
  callback : function(dt) {
    history.value = dt;
  }
})

function open(i) {
  history.value.forEach(c => c.open = false)
  history.value[i].open = true;
}

function close(i) {
  history.value[i].open = false;
}


</script>
<template>
  <div class="container">

    <section class="mt-3">

      <h1>Cronologia</h1>

      <div class="lista mt-5">


        <div v-for="(c,i) in history" :key="i">
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
          
                  <!-- <div class="dropdown">
                    <button class="btn btn-link text-dark" data-bs-toggle="dropdown">
                      <span class="material-symbols-outlined">more_vert</span>
                    </button>
                    <ul class="dropdown-menu">
                      <li><a class="dropdown-item" href="#" @click="removeFromhistory(i)"><span class="material-symbols-outlined">delete</span> Rimuovi</a></li>
                      <li><a class="dropdown-item" href="#"><span class="material-symbols-outlined">ios_share</span> Condividi</a></li>
                    </ul>
                  </div> -->
                </div>
              </div>
              <div class="descrizione"  @click="open(i)">{{ c.description.split("|")[0] }}</div>
              <div class="text-muted">{{ formatDistance(new Date(c.last_seen), new Date(), {locale: it, addSuffix: true}) }}</div>
            </div>
          
          </div>
          <Dettaglio v-if="c.open" :data="c" @close="close(i)" :showRelated="false" class="mb-3" />
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
  display: flex;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 20px;

  .image {
    cursor: pointer;
    img {
      width: 200px;
    }
  }

  .info {
    flex: 1;
  }

  .titolo {
    font-weight: bold;
    cursor: pointer;
  }
  .descrizione {
    font-size: .9rem;
    cursor: pointer;
  }
}
</style>