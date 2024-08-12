<script setup>
import { ref } from 'vue'
import Toast from './Toast.vue'

import useToasts from '@/stores/toasts';
const t = useToasts()

</script>
<template>
    <div id="notification-area">
      <Toast v-for="item in t.toastList" :key="item.id" :content="item.content" :title="item.title" :autohide="item.autohide" :delay="item.delay"/>
    </div>
</template>
<style scoped lang="scss">
#notification-area {
  z-index: 3000;
  position: fixed;
  width: 100vw;
  max-width: 300px;
  top: 65px;
  right: .5rem;
}
</style>