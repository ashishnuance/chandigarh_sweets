<template>
    <section class="cart-section">
        <div class="container">
            <div class="row" v-if="cartListItemCheck">
                <div class="col-12 cart-col" >
                    <div class="card Firstcard cart-card" v-for="cart_product in cartListItem">
                        <div class="thumbCardImg">
                            <img src="{{ cart_product.product_images }}" class="card-img-top" alt="..." style="width:80px;">
                        </div>
                        <div class="card-body p-0 customcard">
                            <div class="carddds">
                                <h5 class="card-title">{{ cart_product.product_name }}</h5>
                                <div class="Qty ">
                                    <p>Qty</p>
                                    <div class="QtyAdd">
                                    <p>+</p>
                                    <h6>{{ cart_product.product_qty }}</h6>
                                    <p>-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="PrductPrice ">
                                <p>Rs {{ cart_product.product_price }}/-</p>
                            </div>
                            <button type="button" @click="removeItem(cart_product.id)">Remove</button>
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                <div class="ProductBtn ">
                    <a href="javascript:void(0);"  @click="postCheckout()" class="AddCardBtn">Place Order</a>
                    <router-link to="/quotation" class="WishCardBtn">Continue Shopping</router-link>
                </div>
                </div>
            </div>
            <div class="row" v-else>
                <div class="col-12 cart-col" >
                    <h2>Your Cart is Empty</h2>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
    import CONFIG from '../config';
    export default {
        data(){
            return{
                cartListItem:[],
                cartListItemCheck:false
            }
        },
        created(){
            this.getCartListItem();
        },
        methods:{
            removeItem(cart_id){
                let api_url = CONFIG.API_URL_ROOT+'/remove-cart-item';
                let auth_user = this.$store.state.token;
                console.log('cart-page',api_url,auth_user);
                let cartBodyItem = new FormData();
                cartBodyItem.append('cart_id',cart_id);
                fetch(api_url,{
                    method:'post',
                    headers:{
                        'Authorization': `Bearer ${auth_user}`
                    },
                    body: cartBodyItem 
                }).then(response => response.json())
                .then(response =>{
                    if(response.data.length && response.data.length>0){
                        console.log(response.data.length)
                        this.cartListItem = response.data;
                        this.cartListItemCheck=true;
                    }
                }).catch(err => console.log('err',err));
            },
            getCartListItem(){
                let api_url = CONFIG.API_URL_ROOT+'/cart-list';
                let auth_user = this.$store.state.token;
                console.log('cart-page',api_url,auth_user)
                fetch(api_url,{
                    method:'get',
                    headers:{
                        'Authorization': `Bearer ${auth_user}`
                    }
                }).then(response => response.json())
                .then(response =>{
                    if(response.data.length && response.data.length>0){
                        console.log(response.data.length)
                        this.cartListItem = response.data;
                        this.cartListItemCheck=true;
                    }
                }).catch(err => console.log('err',err));
            },
            postCheckout(){
                let api_url = CONFIG.API_URL_ROOT+'/checkout';
                let auth_user = this.$store.state.token;
                let checkoutData = {
                    'order_type':'order',
                    'user_id':auth_user.user_id,
                    'company_id':1,
                    'product_data':this.cartListItem
                };
                fetch(api_url,{
                    method:'post',
                    headers:{
                        "Content-Type": "application/json",
                        'Authorization': `Bearer ${auth_user}`
                    },
                    body:JSON.stringify(checkoutData)
                }).then(response => response.json())
                .then(response =>{
                    console.log('checkout',response)
                    this.cartListItemCheck=false;
                }).catch(err => console.log('checkout error',err))
                console.log('checkoutData',checkoutData)
            }
        }
    }
</script>