import { createApp } from 'vue'
import {KadroCore,createKadroRouter,pinia} from 'kadro-core';
import "kadro-core/style.css"
import App from './components/App.vue'

const app = createApp(App);

const env_config = {};

for (const k in import.meta.env) {
    if (k.startsWith("VITE")) {
        env_config[k.replace("VITE_","")] = import.meta.env[k]
    }
}

app.use(KadroCore,env_config);
app.use(pinia.createPinia())

const router = createKadroRouter([
        {
            path: "/",
            name: "Home",
            component: () => import("./views/home/home.vue"),
        },
        {
            path: "/customers",
            name: "Clienti",
            component: () => import("./views/customers/customers.vue"),
        },
        {
            path: "/project/:id",
            name: "Progetto",
            component: () => import("./views/project/project.vue"),
        },
        {
            path: "/contents",
            name: "Contenuti",
            component: () => import("./views/contents/contents.vue"),
        },
        {
            path: "/course/:permalink",
            name: "Corso",
            component: () => import("./views/course/course.vue"),
        },
        {
            path: "/courses",
            name: "Corsi",
            component: () => import("./views/courses/courses.vue"),
        },
        {
            path: "/authors",
            name: "Autori",
            component: () => import("./views/authors/authors.vue"),
        },
        {
            path: "/homepage-settings",
            name: "Gestione Homepage",
            component: () => import("./views/homepage-settings/homepage-settings.vue"),
        },
        {
            path: "/widgets",
            name: "Widgets",
            component: () => import("./views/widgets/widgets.vue"),
        },
        
    ]);

app.use(router)
app.mount("#app")



