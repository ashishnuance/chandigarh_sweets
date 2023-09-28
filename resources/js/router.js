import { createWebHistory,createRouter } from "vue-router";

import home from './pages/home.vue';
import login from './pages/login.vue';
import register from './pages/registration.vue';
import welcome from './components/welcome.vue';


const routes = [
    {
        path:'/front/welcome',
        name:'Welcome',
        component:welcome
    },
    {
        path:'/front/home',
        name:'Home',
        component:home
    },
    {
        path:'/front/login',
        name:'Login',
        component:login
    },
    {
        path:'/front/register',
        name:'Register',
        component:register
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;