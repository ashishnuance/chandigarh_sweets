require('./bootstrap')

import { createApp } from 'vue';
// import Welcome from './components/Welcome'
import router from './router.js'
import App from './layouts/app.vue';
import axios from 'axios';
// Vue.prototype.$http = axios;
import store from './store/index.js';

// new Vue({
//     render: (h) => h(App),
//     store,
//     router, // Add the store to your Vue instance
//   }).$mount('#app');

createApp(App)
    .use(router)
    .use(store)
    .mount('#app')

// const app = createApp({})

// app.component('welcome', Welcome)

// app.mount('#app')