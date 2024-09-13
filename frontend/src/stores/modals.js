import { ref } from 'vue'
import { defineStore } from 'pinia'

const useModals = defineStore('modals', () => {
  const modalList = ref([])

  function msgbox(t) {
    t["id"] = "sspa-modal-"+modalList.value.length;
    modalList.value.push(t)
  }

  

  return { modalList, msgbox }
})

export default useModals;