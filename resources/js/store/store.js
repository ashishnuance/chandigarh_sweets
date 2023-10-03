import { createStore } from 'vuex';


export default createStore({
    state:{
        sharedData: 0,
        isLogin:false,
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
    }
})