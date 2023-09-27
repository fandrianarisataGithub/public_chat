import Home from '../../../pages/Home.vue'
import Login from '../../../pages/security/Login.vue'
import UserRegister from '../../../pages/security/UserRegister.vue'

const routes = [
  { path: '/', component: Home, meta: { requiresAuth: true } },
  { path: '/api/login', component: Login, props: true },
  { path: '/api/register', component: UserRegister, props: true }
]

export default routes;