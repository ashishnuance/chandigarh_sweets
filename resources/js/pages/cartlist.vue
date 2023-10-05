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
                                            <p class="mb-0 mt-1">Variant : <span class="fw-medium">Gray</span></p>
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
                                                <div class="d-inline-flex">
                                                    <select class="form-select form-select-sm w-xl">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option :value="cart_product.product_qty" selected >{{ cart_product.product_qty }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mt-3">
                                                <p class="text-muted mb-2">Total</p>
                                                <h5>$900</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                            </div>
                        </div>
                        <!-- end card -->
            
                        
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
                <!-- end row -->
            
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
            }
        },
        created(){
            this.getCartListItem();
        },
        methods:{
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