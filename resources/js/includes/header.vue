<template>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
            <a class="navbar-brand " href="index.html">
                <router-link to="/home">
                <img v-bind:src="headerSettings.header_logo" alt="" class="img-fluid">
                </router-link>
            </a>
    
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
                aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav ms-auto NavIcon">
                    
                    <li class="nav-item">
                        <img src="img/icon4.png" alt="">
                        <p>messages</p>
                    </li>
                    <li class="nav-item" v-if="!isLogedIn">
                        <router-link to="/app-register">
                        <img src="img/icon5.png" alt="">
                        <p>sign up</p>
                    </router-link>
                    </li>
                    
                    <li class="nav-item">
                        <router-link to="/cart">
                        <!-- <a href="Cart.html"> -->
                            <img src="img/icon5.png" alt="">
                            <span class="addcart">{{ sharedData }}</span>
                        <!-- </a> -->
                        </router-link>
                    </li>
                </ul>
            </div>
            </div>
        </nav>
    </header> 
</template>

<script>
    import CONFIG from '../config';
    export default {
        name:'header',

        data() {
            return {
                headerSettings: [],
                pagination: {},
                cartItemCount:0,
                isLogedIn:false
            };
        },

        created() {
            this.getHeaderSettings();
        },
        mounted(){
            this.getCartCount();
            this.updateSharedData();
            this.checkAuth();
        },
        computed:{
            sharedData() {
                return this.$store.state.sharedData;
            },
        },
        methods: {
            updateSharedData() {
                this.$store.commit('updateSharedData', this.cartItemCount);
            },
            checkAuth(){
                let auth_user = JSON.parse(localStorage.getItem('auth_user'));
                if(auth_user.token && auth_user.token!=''){
                    this.isLogedIn=true;
                }
            },  
            getHeaderSettings() {
                console.log(`token ${CONFIG.AUTH_TOKEN}`);
                let api_url = `${CONFIG.API_URL_ROOT}/app-settings`;
                console.log('api_url',api_url);
                fetch(api_url)
                .then(response => response.json())
                .then(response => {
                    this.headerSettings = response.data.logos;
                    console.log('response',response.data.home_banner[0].image);
                })
                .catch(err => console.log(err));
            },
            getCartCount(){
                let api_url = CONFIG.API_URL_ROOT+'/cart-list';
                let auth_user = JSON.parse(localStorage.getItem('auth_user'));
                fetch(api_url,{
                    method:'get',
                    headers:{
                        'Authorization': `Bearer ${auth_user.token}`
                    }
                }).then(response => response.json())
                .then(response =>{
                    console.log(response.data.length)
                    this.$store.commit('updateSharedData', response.data.length);
                }).catch(err => console.log('err',err));
            }
        }
    }
</script>