<script setup>
import { Toast } from 'bootstrap';
import {onMounted, ref, defineProps} from 'vue';

const el = ref(null);

const props = defineProps(["title","content", "autohide","delay"])

onMounted(() => {
    console.log(el)
    const t = new Toast(el._rawValue)
    t.show()
})


</script>
<template>
     <div ref="el" class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true" :data-bs-autohide="props.autohide" :data-bs-delay="props.delay">
        <template v-if="props.content !== null">
          <div class="toast-header">
            <strong class="me-auto" v-html="props.title || ''" ></strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body" v-if="props.content?.length > 0" v-html="props.content">
          </div>
        </template>
        <template v-else>
          <div class="d-flex">
            <div class="toast-body fw-bold" v-html="props.title">
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </template>
      </div>
</template>
<style scoped>
.toast {
  border-radius: 0;
  background: var(--bs-body-bg);
}
</style>