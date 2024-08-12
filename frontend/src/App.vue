<script setup>
import { RouterLink, RouterView, useRoute } from 'vue-router'
import {ref, onBeforeMount, reactive} from 'vue'
import { useTerraParkStore } from './stores/commons';
import { useProjectStore } from './stores/project';
import { request } from './utils/request';
import ToastArea from './components/ToastArea.vue';

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
      <ToastArea/>
  </div>
  <div v-else class="big-loader">
    <div class="fancy-loader"></div>
  </div>
</template>
<style scoped lang="scss">
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

.fancy-loader {
  --c:no-repeat linear-gradient(var(--bs-gray-500) 0 0);
  background: 
    var(--c),var(--c),var(--c),
    var(--c),var(--c),var(--c),
    var(--c),var(--c),var(--c);
  background-size: 16px 16px;
  animation: 
    l32-1 2s infinite,
    l32-2 2s infinite;
}
@keyframes l32-1 {
  0%,100% {width:45px;height: 45px}
  35%,65% {width:65px;height: 65px}
}
@keyframes l32-2 {
  0%,40%  {background-position: 0 0,0 50%, 0 100%,50% 100%,100% 100%,100% 50%,100% 0,50% 0,  50% 50% }
  60%,100%{background-position: 0 50%, 0 100%,50% 100%,100% 100%,100% 50%,100% 0,50% 0,0 0,  50% 50% }
}

  
}


</style>
