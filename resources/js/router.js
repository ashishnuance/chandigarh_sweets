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
        component:welcome,
        meta:{
            isAuth:false
        }
    },
    {
        path:'/home',
        name:'Home',
        component:home,
        meta:{
            isAuth:false
        }
    },
    {
        path:'/app-login',
        name:'Login',
        component:login,
        meta:{
            isAuth:false
        }
    },
    {
        path:'/app-register',
        name:'Register',
        component:register,
        meta:{
            isAuth:false
        }
    },
    {
        path:'/password',
        name:'Password',
        component:password,
        meta:{
            isAuth:false
        }
    },
    {
        path:'/quotation',
        name:'quotation',
        component:quotation,
        meta:{
            isAuth:true
        }
    },
    {
        path:'/orders',
        name:'orders',
        component:orders,
        meta:{
            isAuth:true
        }
    },
    {
        path:'/product',
        name:'product',
        component:product,
        meta:{
            isAuth:true
        }
    },
    {
        path:'/cart',
        name:'cart',
        component:cart,
        meta:{
            isAuth:true
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to,from,next) => {
    if(to.meta.isAuth){
        let auth_user = localStorage.getItem('auth_user');
        if(!auth_user){
            next({name:'Login'});
        }
    }
    next()
})

export default router;