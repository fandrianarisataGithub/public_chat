import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes/route.js';
import  createStore from './stores/authentication-store.js';

// Utilisez le routeur Vue Router 4
const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    console.log('routed authentication');
    if (createStore.getters.isAuthenticated !== false) {
      console.log('user authenticated: ' + createStore.getters.isAuthenticated);
      next();
    } else {
      console.log('page should be redirected');
      next({
        path: '/api/login',
        query: { redirect: to.fullPath },
      });
    }
  } else {
    next();
  }
});

export default router;