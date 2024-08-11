import { reactive } from 'vue'
import { defineStore } from 'pinia'
import { request } from '@/utils/request'

export const useProjectStore = defineStore('project', () => {
  const project = reactive({

  })
  
  const init = () => {
    return new Promise(resolve => {

      request({
        task : "public/projectInfo",
        callback : async function(dt) {
          Object.assign(project, dt);
          resolve(true);
        }
      })

    })
  }

  const getTheme = () => {
    return project.theme;
  }

  const getTitle = () => {
    return project.title;
  }

  const getCustomer = () => {
    return project.customer;
  }

  const getContacts = () => {
    return {
      email : project.cs_email,
      phone : project.cs_phone,
      hours : project.cs_hours
    }
  }

  return { project, init, getTheme, getTitle, getContacts, getCustomer}
})
