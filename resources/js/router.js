import { createWebHistory,createRouter } from "vue-router";

import home from './pages/home.vue';
import login from './pages/auth/login.vue';
import register from './pages/auth/registration.vue';
import welcome from './components/welcome.vue';
import password from './pages/auth/password';
import quotation from './pages/quotation';
import orders from './pages/orders';
import product from './pages/product';
import cart from './pages/cartlist';


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
    },
    {
        path:'/password',
        name:'Password',
        component:password
    },
    {
        path:'/quotation',
        name:'quotation',
        component:quotation
    },
    {
        path:'/orders',
        name:'orders',
        component:orders
    },
    {
        path:'/product',
        name:'product',
        component:product
    },
    {
        path:'/cart',
        name:'cart',
        component:cart
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;