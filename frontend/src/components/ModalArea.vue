<script setup>
import useModals from '../stores/modals.js'
import Modal from './Modal.vue'

const m = useModals();
</script>
<template>
    <div id="modal-area">
      <Modal v-for="(modal,i) in m.modalList" :key="i" :title="modal.title" :content="modal.content" :actions="modal.actions" :canDismiss="modal.canDismiss" :size="modal.size"/>
    </div>
</template>
<style scoped lang="scss">

</style>