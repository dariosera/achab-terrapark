<script setup>
import { RouterLink, RouterView, useRoute } from 'vue-router'
import {ref, onBeforeMount, reactive} from 'vue'
import { useTerraParkStore } from './stores/commons';
import { useProjectStore } from './stores/project';
import { request } from './utils/request';

import Topbar from './components/Topbar.vue';
import Footer from './components/Footer.vue';

import { useI18n } from 'vue-i18n';
import Trans from './i18n/translations';

import { isLogged } from './utils/auth';

Trans.switchLanguage(Trans.guessDefaultLocale())

const route = useRoute()

const projectReady = ref(false)
const project = reactive({})
const ps = useProjectStore()

onBeforeMount(async () => {
  await ps.init()
  projectReady.value = true
})

const tps = useTerraParkStore()
if (isLogged()) {
  tps.init()
}
</script>
<template>
  <div v-if="projectReady">
      <Topbar v-if="!route.meta.isPublic" />
      <main>
        <RouterView />
      </main>
      <Footer v-if="!route.meta.isPublic" />
  </div>
  <div v-else class="big-loader">
    <div class="spinner-border text-primary" role="status">
    </div>
  </div>
</template>
<style scoped>
main {
  min-height: calc(100vh - 250px);
}

.big-loader {
  display: grid;
  place-items: center;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}


</style>
