import { createApp } from 'vue';
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap"
import './assets/styles/app.scss'
import App from './App.vue';
import createStore from './assets/js/stores/authentication-store.js'; // Assurez-vous que le chemin est correct
import router from './assets/js/router-app.js';

const app = createApp(App);


app.use(createStore);
app.use(router);

app.mount('#app');
