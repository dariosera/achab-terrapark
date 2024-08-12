import { ref } from 'vue'
import { defineStore } from 'pinia'

const useToasts = defineStore('toasts', () => {
  const toastList = ref([])

  function addToast(t) {
    t["id"] = "kadro-notification-"+toastList.value.length;
    if ("undefined" === typeof(t.content)) t.content = null;
    toastList.value.push(t)
  }

  

  return { toastList, addToast }
})

export default useToasts;