import { createRouter as createVueRouter, createWebHistory } from 'vue-router'
import { isLogged, AFTER_LOGIN_REDIRECT } from '@/utils/auth'
import { useTerraParkStore } from '@/stores/commons'
import { request } from '@/utils/request';
import useToasts from '@/stores/toasts';
import useModals from '@/stores/modals';
import CompletaProfilo from '@/components/CompletaProfilo.vue';

export function createRouter(app) {
  let checkProfileData = true;
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
        path: "/conferma-email/:vid/:uid",
        name: "Conferma indirizzo email",
        component: () => import("../views/conferma-email/view.vue"),
        meta: { isPublic: true, fullPage: true } 
      },
      {
        path: "/reimposta-password/:rid/:uid",
        name: "Reimposta password",
        component: () => import("../views/reimposta-password/view.vue"),
        meta: { isPublic: true, fullPage: true }
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
        path: '/watch/:permalink',
        name: 'watch',
        component: () => import('../views/watch/watch.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/autori',
        name: 'autori',
        component: () => import('../views/autori/autori.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/autore/:id',
        name: 'autore',
        component: () => import('../views/autore/autore.vue'),
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
        path: '/tema',
        name: 'tema',
        component: () => import('../views/tema/tema.vue'),
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
        path: '/privacy-policy',
        name: 'Privacy Policy',
        component: () => import('../views/privacy-policy/view.vue'),
        //beforeEnter: createAuthGuard(app)
      },
      {
        path: '/termini-e-condizioni',
        name: 'Termini e Condizioni',
        component: () => import('../views/termini-e-condizioni/view.vue'),
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
    const l = isLogged()
    if (!l && to.name !== 'login' && to?.meta.isPublic !== true) {
      // redirect the user to the login page
  
      sessionStorage.setItem(AFTER_LOGIN_REDIRECT, to.fullPath);
      return { name: 'login' }
    }

    if (l && checkProfileData) {
      console.log("checkprofiledata");
      checkProfileData = false;
      request({
        task : "profile/checkProfileData",
        data : {},
        callback : function(dt) {
          if (dt.action_required) {
            useModals().msgbox({
              title : "Completa il profilo",
              content: CompletaProfilo,
              canDismiss: false,
            })
            
          } else {
            // Alcuni dati possibili da compilare
          }
        }
      })
    }
    

    const tps = useTerraParkStore();
    await tps.init();
    
  })

  return _router;

} 
