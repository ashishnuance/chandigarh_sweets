import { createWebHistory,createRouter } from "vue-router";

import home from './pages/home.vue';
import login from './pages/login.vue';
import register from './pages/registration.vue';
import welcome from './components/welcome.vue';


const routes = [
    {
        path:'/welcome',
        name:'Welcome',
        component:welcome
    },
    {
        path:'/home',
        name:'Home',
        component:home
    },
    {
        path:'/login',
        name:'Login',
        component:login
    },
    {
        path:'/register',
        name:'Register',
        component:register
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;