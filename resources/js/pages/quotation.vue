<template>
    <section class="QuotBannerPopup">
      <div class="container">
         <div class="row">
            <div class="col-lg-12  d-flex justify-content-center">
               <div class="QuotPopup">
                  <h2>Quotation</h2>
                  <div class=" popupCard">
                     <div class="QuotPopupInput">
                        <input type="text" placeholder="Search Items">
                        <button><i class="fa-solid fa-magnifying-glass"></i></button>
                     </div>
                     <div class="itemsCard">
                        <div class="AddCard" v-for="product in products">
                           <router-link to="/product">
                              <div class="item">{{ product.product_name }}</div>
                           </router-link>
                           <input type="number" name="qty" value="1" class="qty-{{product.id}}"/>
                           <button class="btn btn-danger" @click="addToCart(product.id)">Add</button>
                           
                        </div>
                     </div>
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
         return{
            products:[]
         }
      },
      created(){
         this.getProductsList();

      },
      computed:{
         // cartItemCountValue(){
         //       return this.$store.state.cartItemCountValue;
         // },
         sharedData() {
            return this.$store.state.sharedData;
         },
      },
      methods:{
         
         getProductsList(){
            const api_url = `${CONFIG.API_URL_ROOT}/products`;
            console.log('api_url',api_url);
            fetch(api_url, {
               method:'get',
               headers:{
                  'Authorization': `Bearer ${CONFIG.AUTH_TOKEN}`
               }
            })
            .then(response => response.json())
            .then(response => {
               console.log('response',response);
               this.products = response.data;
            })
            .catch(err => console.log('err',err));
         },
         async addToCart(productId){
            console.log("here",productId)
            let cartProduct = new FormData();
            
            cartProduct.append("product_id",productId);
            cartProduct.append("order_type",'order');
            cartProduct.append("product_qty",1);
            cartProduct.append("product_price",20);
            cartProduct.append("product_variant_id",38);
            
            const api_url = CONFIG.API_URL_ROOT+'/add-to-cart';
            console.log('api_url',api_url,cartProduct);
            await fetch(api_url, {
               method:'POST',
               headers:{
                  'Authorization': `Bearer 10|kH7UaGq21KOJRKHtRcmaBJ1cYIoIURcV2y8jwfwA62124d08`
               },
               body:cartProduct
            })
            .then(response => response.json())
            .then(response => {
               console.log('response',response);
               // this.products = response.data;
               this.$store.commit('updateSharedData', response.data);
            })
            .catch(err => console.log('err',err));
         }
      }
   }
</script>