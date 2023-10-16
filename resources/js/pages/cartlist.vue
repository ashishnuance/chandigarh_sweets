<template>
    <section class="cart-section">
        <div class="py-5">
            <div class="container">
                <div class="row" v-if="cartListItemCheck">
                    <div class="col-xl-8">
                        <div class="card border shadow-none" v-for="cart_product in cartListItem">
                            <div class="card-body">
            
                                <div class="d-flex align-items-start border-bottom pb-3">
                                    <div class="me-4">
                                        <img v-bind:src="cart_product.product_images" alt="" class="avatar-lg rounded">
                                    </div>
                                    <div class="flex-grow-1 align-self-center overflow-hidden">
                                        <div>
                                            <h5 class="text-truncate font-size-18"><a href="#" class="text-dark">{{ cart_product.product_name }}</a></h5>
                                            <p class="mb-0 mt-1">Variant : <span class="fw-medium">{{ cart_product.products_variants }}</span></p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 ms-2">
                                        <ul class="list-inline mb-0 font-size-16">
                                            <li class="list-inline-item">
                                                <a href="javascript:void(0);" @click="removeItem(cart_product.id)" class="text-muted px-1">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
            
                                <div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mt-3">
                                                <p class="text-muted mb-2">Price</p>
                                                <h5 class="mb-0 mt-2"><span class="text-muted me-2"><del class="font-size-16 fw-normal">Rs.{{ cart_product.product_price }}</del></span>Rs.{{ cart_product.product_price }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="mt-3">
                                                <p class="text-muted mb-2">Quantity</p>
                                                <div class="Qty">
                                                    <div class="qty-input">
                                                        <button class="qty-count qty-count--minus" data-action="minus" type="button">-</button>
                                                        <input class="product-qty" type="number" name="product-qty" min="0" max="10">
                                                        <button class="qty-count qty-count--add" data-action="add" type="button">+</button>
                                                    </div>
                                                    <div class="Variant">
                                                        <!-- <select class="form-control" v-if="productDetail.product_variation">
                           <option>Select Variant</option>
                           <option v-for="pro_vari in productDetail.product_variation" :key="pro_vari.id" v-value="pro_vari.id">{{ pro_vari.variation_value }} {{ pro_vari.variation_type }}</option>
                        </select> -->
                    </div>
                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-3">
                                                <p class="text-muted mb-2">Total</p>
                                                <h5>Rs {{ cart_product.product_price*cart_product.product_qty }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                            </div>
                        </div>
                        <!-- end card -->
                        
                        <div class="row my-4">
                            <div class="col-sm-6">
                                <div class="ProductBtn justify-content-start">
                                    <router-link to="/{{cartListItem[0].order_type}}" class="btn theme-btn">
                                    <i class="fa fa-arrow-left me-1"></i> Continue Shopping </router-link>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-sm-6">
                                <div class="text-sm-end mt-2 mt-sm-0 ProductBtn justify-content-end">
                                    <a href="javascript:void(0);" @click.prevent="postCheckout()" class="btn theme-btn">
                                        <i class="fa fa-bag me-1"></i> Checkout </a>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        
                    </div>
            
                    <div class="col-xl-4">
                        <div class="mt-5 mt-lg-0">
                            <div class="card border shadow-none">
                                <div class="card-header bg-transparent border-bottom py-3 px-4">
                                    <h5 class="font-size-16 mb-0">Order Summary <span class="float-end">#MN0124</span></h5>
                                </div>
                                <div class="card-body p-4 pt-2">
            
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <tbody>
                                                <tr>
                                                    <td>Sub Total :</td>
                                                    <td class="text-end">$ {{subTotal}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Discount : </td>
                                                    <td class="text-end">- $ {{discountPrice}}</td>
                                                </tr>
                                                
                                                <tr class="bg-light">
                                                    <th>Total :</th>
                                                    <td class="text-end">
                                                        <span class="fw-bold">
                                                            $ {{totalPrice}}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end table-responsive -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" v-else>
                    <div class="CartEmpty">
                    <div class="EmptyImg">
                        <img src="http://localhost:8000/img/emptyCart.jpg" alt="">
                       
                    </div>
                   <h2>Your Cart is Empty</h2> 
                   <p>Add something to make me happy</p>
                   <router-link to="/" class="EmptyBtn">Continue Shoping</router-link>   
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
                cartListItem:[],
                cartListItemCheck:false,
                totalPrice:0,
                discountPrice:0,
                subTotal:0,
                product_qty:0
            }
        },
        created(){
            this.getCartListItem();
        },
        methods:{
            increaseQty(pro_qty){
                console.log(pro_qty,this.product_qty)
                this.product_qty = (pro_qty+1);
                console.log(this.product_qty)
            },
            decreaseQty(pro_qty){
                console.log(pro_qty,this.product_qty)
                if(pro_qty>1){
                this.product_qty = (pro_qty-1);
                }else{
                this.product_qty = pro_qty;
                }
                console.log(this.product_qty)
            },
            removeItem(cart_id){
                let cartItemIndex = this.cartListItem.findIndex(x => x.id === cart_id);
                let api_url = CONFIG.API_URL_ROOT+'/remove-cart-item';
                let auth_user = this.$store.state.token;
                console.log('cart-page',api_url,auth_user,cartItemIndex,this.cartListItem.length);
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
                    this.cartListItem.splice(cartItemIndex,1)
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
                    console.log('response',response);
                    if(response.data.length && response.data.length>0){
                        console.log(response.data.length)
                        this.cartListItem = response.data;
                        this.cartListItemCheck=true;
                    }
                    if(response.success===false){
                        this.cartListItemCheck=false;
                    }
                }).catch(err => console.log('err',err));
            },
            postCheckout(){
                let api_url = CONFIG.API_URL_ROOT+'/checkout';
                let auth_user = this.$store.state.token;
                let checkoutData = {
                    'order_type':this.cartListItem[0].order_type,
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
            },
            
        }
    }
</script>