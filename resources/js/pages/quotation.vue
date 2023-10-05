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
                        <div v-if="!products.length">No Product Found</div>
                        <div class="AddCard" v-for="product in products" >
                           <form method="post" @submit.prevent="addToCart(product)" v-if="product.product_order_type=='quotation'">
                              <router-link :to="{name:'Product',params:{slug:product.product_slug}}">
                                 <div class="item">{{ product.product_name }}</div>
                              </router-link>
                              <input type="number" v-model="product_qty" />
                              <button class="btn btn-danger" type="submit">Add</button>
                           </form>
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
            products:[],
            noProductFound:false
         }
      },
      created(){
         this.getProductsList();

      },
      methods:{
         
         getProductsList(){
            const api_url = `${CONFIG.API_URL_ROOT}/products`;
            let auth_user = this.$store.state.token;
            console.log('api_url',api_url,auth_user);
            fetch(api_url, {
               method:'get',
               headers:{
                  'Authorization': `Bearer ${auth_user}`
               }
            })
            .then(response => response.json())
            .then(response => {
               console.log('response',response);
               this.products = response.data;
               
            })
            .catch(err => console.log('err get product',err));
         },
         async addToCart(productElement){
            let app = this;
            console.log("here",productElement,productElement['discount'],app.product_qty)
            let cartProduct = new FormData();
            
            cartProduct.append("product_id",productElement['id']);
            cartProduct.append("order_type",'order');
            cartProduct.append("product_qty",app.product_qty);
            cartProduct.append("product_price",productElement['price']);
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