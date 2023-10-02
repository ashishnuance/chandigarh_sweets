import { createStore } from 'vuex';


export default createStore({
    state:{
        sharedData: 'Initial Value',
    },
    mutations:{
        // updateCartItemValueData(cartItemCountValue,newCartItemValue){
        //     this.state.cartItemCountValue = newCartItemValue;
        // }
        updateSharedData(state, newValue) {
            state.sharedData = newValue;
        },
    }
})