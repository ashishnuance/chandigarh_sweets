require('./bootstrap')

import { createApp } from 'vue';
// import Welcome from './components/Welcome'
import router from './router.js'
import App from './layouts/app.vue';

createApp(App)
    .use(router)
    .mount('#app')

// const app = createApp({})

// app.component('welcome', Welcome)

// app.mount('#app')