import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes/route.js';
import  store from './stores/index';

// Utilisez le routeur Vue Router 4
const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    console.log('routed authentication');
    if (store.getters.ISAUTHISUSERAUTHENTICATED !== false) {
      console.log('user authenticated: ' + store.getters.ISAUTHISUSERAUTHENTICATED);
      next();
    } else {
      console.log('page should be redirected');
      next({
        path: '/login',
        query: { redirect: to.fullPath },
      });
    }
  } else {
    next();
  }
});

export default router;