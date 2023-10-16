<template>
   <section class="Product">
      <div class="container">
         <div class="row">
            <div class="col-lg-5 d-flex justify-content-center">
               <div class="card Firstcard">
                  <div class="thumbCardImg">
                     <span v-for="productImage in productDetail.product_images" :key="productImage.id">
                     <img v-bind:src="productImage.image" class="card-img-top" alt="...">
                     </span>
                  </div>
               </div>
            </div>
            <div class="col-lg-7 d-flex align-items-center">
               <div class="ProductContent ">
                  <div class="ProductHead">
                     <h5>{{ productDetail.product_name }}</h5>
                  </div>
                  <div class="PrductPrice ">
                     <p>Rs {{productPrice}}/-</p>
                     <!-- <p class="Stock"><span></span>In Stock</p> -->
                  </div>
                  <div class="dicription">
                     <p>{{ productDetail.description }}</p>
                  </div>
                  <div class="Qty">
                     <div class="qty-input">
                        <button class="qty-count qty-count--minus" @click.prevent="decreaseQty(product_qty)" data-action="minus" type="button">-</button>
                        <input class="product-qty" type="number" name="product-qty" min="0" max="10" v-model="product_qty">
                        <button class="qty-count qty-count--add" @click.prevent="increaseQty(product_qty)" data-action="add" type="button">+</button>
                     </div>
                     <div class="Variant">
                        
                        <!-- <select class="form-control" v-if="productDetail.product_variation">
                           <option>Select Variant</option>
                           <option v-for="pro_vari in productDetail.product_variation" :key="pro_vari.id" v-value="pro_vari.id">{{ pro_vari.variation_value }} {{ pro_vari.variation_type }}</option>
                        </select> -->
                     </div>
                  </div>
                  <div class="ProductBtn ">
                     <a href="javascript:void(0);" @click.prevent="addToCart(productDetail)" class="AddCardBtn">Add card</a>
                  </div>
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
         return {
            productDetail:[],
            productPrice:0,
            product_qty:1
         }
      },
      created(){
         this.getProductDetail()
         console.log(this.$route.params.slug);
      },
      methods:{
         increaseQty(pro_qty){
            this.product_qty = (pro_qty+1);
         },
         decreaseQty(pro_qty){
            if(pro_qty>1){
               this.product_qty = (pro_qty-1);
            }else{
               this.product_qty = pro_qty;
            }
         },
         getProductVariant(){
            console.log('element');
         },
         async getProductDetail(){
            let api_url = CONFIG.API_URL_ROOT+'/products-detail/'+this.$route.params.slug;
            let auth_user = this.$store.state.token;
            console.log('api_url',api_url,auth_user)
            await fetch(api_url,{
               headers:{
                  'Authorization': `Bearer ${auth_user}`
               }
            }).then(response => response.json())
            .then(response =>{
               this.productDetail = response.data;
               console.log('result',response.data,this.productDetail.product_variation);
               console.log('product_variation',response.data.product_variation);
               if(response.data.product_variation.length>0){
                  this.productPrice = response.data.product_variation[0].main_price;
               }
            })
         },
         async addToCart(productElement){
            let app = this;
            console.log("here",productElement,productElement['discount'],app.product_qty)
            let cartProduct = new FormData();
            
            cartProduct.append("product_id",productElement['id']);
            cartProduct.append("order_type",'order');
            cartProduct.append("product_qty",app.product_qty);
            cartProduct.append("product_price",app.productPrice);
            cartProduct.append("product_variant_id",productElement['product_variation'][0]['id']);
            
            const api_url = CONFIG.API_URL_ROOT+'/add-to-cart';
            let auth_user = this.$store.state.token;
            console.log('api_url',api_url,cartProduct);
            await fetch(api_url, {
               method:'POST',
               headers:{
                  'Authorization': `Bearer ${auth_user}`
               },
               body:cartProduct
            })
            .then(response => response.json())
            .then(response => {
               console.log('response',response);
               // this.products = response.data;
               this.$store.commit('updateSharedData', response.data);
               this.$store.dispatch('setCartCount',response.data)
            })
            .catch(err => console.log('err',err));
         }
      }  
   }
</script>