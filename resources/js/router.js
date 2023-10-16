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
import thankyou from './pages/thankyou';


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
        path:'/',
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
        path:'/product/:slug',
        name:'Product',
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
    },
    {
        path:'/thankyou',
        name:'Thankyou',
        component:thankyou,
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
    let auth_user = localStorage.getItem('auth_user');
    if(to.meta.isAuth){
        if(!auth_user){
            next({name:'Login'});
        }
    }
    if(to.name=='Login' || to.name=='Register'){
        if(auth_user){
            next({name:'Home'});
        }
    }
    next()
})

export default router;