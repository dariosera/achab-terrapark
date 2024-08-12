import "./assets/core.scss"

import 'bootstrap';

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { createAuth0 } from "@auth0/auth0-vue";



import App from './App.vue'
import {createRouter} from './router'

import i18n from "./i18n"

const app = createApp(App)

app.use(createPinia())
app.use(createRouter(app))
app.use(i18n)
app.use(
    createAuth0({
      domain: import.meta.env.VITE_AUTH0_DOMAIN,
      clientId: import.meta.env.VITE_AUTH0_CLIENTID,
      authorizationParams: {
        redirect_uri: window.location.origin,
        audience: import.meta.env.VITE_API_ENDPOINT
      }
    })
  )



app.mount('#app')

if (process.env.NODE_ENV === "production") {
  console.log = function () {};
}
