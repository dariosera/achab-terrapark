<script setup>
import { Modal } from 'bootstrap';
import {onMounted, ref, defineProps, defineEmits, useSlots} from 'vue';

const el = ref(null);
const classes = ref(null)
const t = ref(null);

const props = defineProps(["title","content","actions","canDismiss","size","id"])

const emit = defineEmits(["ready", "hidden"])


onMounted(function() {
    const options = {}
    if (props.canDismiss === false) {
      options.backdrop = 'static';
      options.keyboard = false;
    }

    
    if (props.size) {
      classes.value = "modal-"+props.size;
    }

    t.value = new Modal(el._rawValue, options)

    if (!useSlots().default) {
      t.value.show()
    }

    el._rawValue.addEventListener("hidden.bs.modal", function(e) {
      emit('hidden', e)
    })


    emit("ready", t.value)
})

const sizes = {
  'modal-sm' : props.size === 'sm',
  'modal-lg' : props.size === 'lg',
  'modal-xl' : props.size === 'xl',
  'modal-fullscreen' : props.size === 'fullscreen',
  'modal-xl modal-fullscreen-sm-down' : props.size === 'fullscreen-sm-down',
  'modal-xl modal-fullscreen-md-down' : props.size === 'fullscreen-md-down',
  'modal-xl modal-fullscreen-lg-down' : props.size === 'fullscreen-lg-down',
  'modal-xl modal-fullscreen-xl-down' : props.size === 'fullscreen-xl-down',
  'modal-xl modal-fullscreen-xxl-down' : props.size === 'fullscreen-xxl-down',
}

function defineActions(a) {
  props.actions = a
}
</script>
<template>
    <div ref="el" :id="props.id" class="modal" :class="classes" tabindex="-1">
  <div class="modal-dialog" :class="sizes">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{props.title}}</h5>
        <button v-if="props.canDismiss !== false" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div v-if="typeof(props.content)==='string'" v-html="props.content"></div>
        <template v-else>
          <component v-if="t !== null" :is="props.content" :modal="t"></component>
        </template>
      </div>
      
      
      <div v-if="props.actions && props.actions.length > 0" class="modal-footer">
        <button v-for="(action,i) in props.actions" :key="i" class="btn" :class="action.class" @click="action.callback(el,t)" :data-bs-dismiss="action.dismiss===false?false:'modal'" v-html="action.text"></button>

      </div>
    </div>
  </div>
</div>
</template>