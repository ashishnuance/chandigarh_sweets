import { createStore } from 'vuex';


export default createStore({
    state:{
        sharedData: 0,
        isLogin:false,
        cartItemCount:0,
        token: localStorage.getItem('auth_user') || 0
    },
    mutations:{
        // updateCartItemValueData(cartItemCountValue,newCartItemValue){
        //     this.state.cartItemCountValue = newCartItemValue;
        // }
        updateSharedData(state, newValue) {
            state.sharedData = newValue;
        },
        updateLoginStatus(state, newValue) {
            state.isLogin = newValue;
        },
        UPDATE_TOKEN(state,payload){
            state.token=payload;
        },
        UPDATE_CARTCOUNT(state,payload){
            state.cartItemCount=payload;
        }
    },
    actions:{
        setToken(context,payload){
            localStorage.setItem('auth_user',payload);
            context.commit('UPDATE_TOKEN',payload);
        },
        removeToken(context){
            localStorage.removeItem('auth_user')
            context.commit('UPDATE_TOKEN',0)
        },
        setCartCount(context,payload){
            localStorage.setItem('cart_count',payload)
            context.commit('UPDATE_CARTCOUNT',payload);
        }
    },
    getters:{
        getToken:function(state){
            return state.token;
        }, 
        getCartCount:function(state){
            return state.cartItemCount;
        } 
    }
})
