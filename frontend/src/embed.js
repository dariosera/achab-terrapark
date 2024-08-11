import "./assets/core.scss"
import 'bootstrap';

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import Embed from "./components/Embed.vue";
import i18n from "./i18n"

const app = createApp(Embed)

app.use(createPinia())
app.use(i18n)
app.mount('#widget')
