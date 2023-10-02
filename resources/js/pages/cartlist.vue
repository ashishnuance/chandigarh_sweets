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
                    <a href="quot-order.html" class="AddCardBtn">Place Order</a>
                    <a href="quot-order.html" class="WishCardBtn">Continue Shopping</a>
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
                console.log('cart-page',api_url)
                fetch(api_url,{
                    method:'get',
                    headers:{
                        'Authorization': `Bearer ${CONFIG.AUTH_TOKEN}`
                    }
                }).then(response => response.json())
                .then(response =>{
                    console.log(response.data.length)
                    this.cartListItem = response.data;
                }).catch(err => console.log('err',err));
            }
        }
    }
</script>