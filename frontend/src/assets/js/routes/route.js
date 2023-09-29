import Home from '../../../pages/Home.vue'
import Chat from '../../../pages/Chat.vue'
import Inbox from '../../../pages/Inbox.vue'
import Login from '../../../pages/security/Login.vue'
import UserRegister from '../../../pages/security/UserRegister.vue'

const routes = [
  { path: '/', component: Home, meta: { requiresAuth: true } },
  { path: '/chat', component: Chat, meta: { requiresAuth: true } },
  { path: '/inbox', component: Inbox, meta: { requiresAuth: true } },
  { path: '/login', component: Login, props: true },
  { path: '/register', component: UserRegister, props: true }
]

export default routes;