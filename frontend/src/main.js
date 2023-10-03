import { createApp } from 'vue';
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap"
import './assets/styles/app.scss'
import router from './assets/js/router-app.js';
import App from './App.vue';
import Cookies from 'js-cookie';
import store from './assets/js/stores/index.js';
import 'font-awesome/css/font-awesome.css';
//import './assets/js/bloc';
//console.log(FontAwesomeIcon)
const app = createApp(App);
const authToken = Cookies.get('authToken');

if (authToken) {
  store.commit('change', true);
}
else{
    store.commit('change', false);
    router.push('/login');
}
app.use(store);
app.use(router);
app.mount('#app');
