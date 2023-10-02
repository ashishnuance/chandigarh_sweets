<template>
    <section class="cart-section">
        <div class="container">
            <div class="row">
                <div class="col-12 cart-col">
                    <div class="card Firstcard cart-card" v-for="cart_product in cartListItem">
                        <div class="thumbCardImg">
                            <img src="img/Group 22.png" class="card-img-top" alt="...">
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
        </div>
    </section>
</template>
<script>
    import CONFIG from '../config';
    export default {
        data(){
            return{
                cartListItem:[]
            }
        },
        created(){
            this.getCartListItem();

        },
        methods:{
            getCartListItem(){
                let api_url = CONFIG.API_URL_ROOT+'/cart-list';
                let auth_user = JSON.parse(localStorage.getItem('auth_user'));
                console.log('cart-page',api_url)
                fetch(api_url,{
                    method:'get',
                    headers:{
                        'Authorization': `Bearer ${auth_user.token}`
                    }
                }).then(response => response.json())
                .then(response =>{
                    console.log(response.data.length)
                    this.cartListItem = response.data;
                }).catch(err => console.log('err',err));
            },
            postCheckout(){
                let api_url = CONFIG.API_URL_ROOT+'/checkout';
                let auth_user = JSON.parse(localStorage.getItem('auth_user'));
                let checkoutData = {
                    'order_type':'order',
                    'user_id':auth_user.user_id,
                    'company_id':1,
                    'product_data':this.cartListItem
                };
                fetch(api_url,{
                    method:'post',
                    headers:{
                        'Authorization': `Bearer ${auth_user.token}`
                    },
                    body:JSON.stringify(checkoutData)
                }).then(response => response.json())
                .then(response =>{
                    console.log('checkout',response)
                }).catch(err => console.log('checkout error',err))
                console.log('checkoutData',checkoutData)
            }
        }
    }
</script>