import { createApp } from 'vue';
const app = createApp({});
//element

//bloc

//layout
import Header from './../../components/layout/Header.vue';
import Footer from './../../components/layout/Footer.vue';

// const componentsToRegister = {
//     Header,
//     Footer
// };

// Object.entries(componentsToRegister).forEach(([componentName, component]) => {
//     app.component(componentName, component);
// });

app.component('Header', Header);
app.component('Footer', Footer);

export default app;