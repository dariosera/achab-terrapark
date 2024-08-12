import { createRouter as createVueRouter, createWebHistory } from 'vue-router'
import { isLogged } from '@/utils/auth'

export function createRouter(app) {
  const _router = createVueRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
      {
        path: '/login',
        name: 'login',
        component: () => import('../views/login/login.vue'),
        meta : {
          isPublic: true,
        }
      },
      {
        path: '/registrati',
        name: 'registrati',
        component: () => import('../views/registrati/registrati.vue'),
        meta : {
          isPublic: true,
        }
      },
      {
        path: '/recupero-password',
        name: 'Recupero password',
        component: () => import('../views/recupero-password/recupero-password.vue'),
        meta : {
          isPublic: true,
        }
      },
      {
        path: '/',
        name: 'home',
        component: () => import('../views/home/home.vue')
      },
      {
        path: '/catalogo',
        name: 'catalogo',
        component: () => import('../views/catalogo/catalogo.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/corso/:permalink',
        name: 'corso',
        component: () => import('../views/corso/corso.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/relatori',
        name: 'relatori',
        component: () => import('../views/relatori/relatori.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/relatore/:id',
        name: 'relatore',
        component: () => import('../views/relatore/relatore.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/profilo',
        name: 'profilo',
        component: () => import('../views/profilo/profilo.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/temi',
        name: 'temi',
        component: () => import('../views/temi/temi.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/preferiti/:tipo',
        name: 'preferiti',
        component: () => import('../views/preferiti/preferiti.vue'),
      },
      {
        path: '/cronologia',
        name: 'cronologia',
        component: () => import('../views/cronologia/cronologia.vue'),
      },
      {
        path: '/contatti',
        name: 'contatti',
        component: () => import('../views/contatti/contatti.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/attestati',
        name: 'attestati',
        component: () => import('../views/attestati/attestati.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/:pathMatch(.*)*',
        name: '404',
        component: () => import('../components/NotFound.vue')
      }
    ],
    scrollBehavior(to, from, savedPosition) {
      if (to.hash) {
        return {
          el: to.hash,
          behavior: 'smooth',
        }
      } else {
        return {
          top: 0
        }
      }
    }
  })

  _router.beforeEach(async (to, from) => {
    if (!isLogged() && to.name !== 'login' && to?.meta.isPublic !== true) {
      // redirect the user to the login page
  
      sessionStorage.setItem("sspa_after_login_redirect", to.fullPath);
  
      return { name: 'login' }
    }
  })

  return _router;

} 
