<template>
   <section class="LoginSection">
      <div class="container">
         <div class="row">
            <div class="col-lg-12 d-flex align-items-center justify-content-center">
               <div class="PswrdPopup">
                  <h2>Sign In</h2>
                  <span>{{error}}</span>
                  <div class=" PswrdCard">
                     <form method="post" @submit.prevent="register">
                        <div class="BoxInput">
                           <label class="form-label">Email ID</label>
                           <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" v-model="email">
                        </div>
                        <div class="BoxInput">
                           <label class="form-label">Vendor Code</label>
                           <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="codeHelp" v-model="invite_code">
                        </div>
                        <div class="PswrdBtn">
                           <button type="submit" class="btn btn-default">Submit</button>
                           <router-link to="/app-login">Login</router-link>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</template>
<script>
   import CONFIG from '../../config';
   import { inject } from 'vue'
   const swal = inject('$swal')
   export default {
      data(){
         return {
            error:'',
         };
      },
      methods:{
         async register(){
            var app = this;
            let registerData = new FormData();
            registerData.append('invite_code',app.invite_code);
            registerData.append('email',app.email);
            let api_url = CONFIG.API_URL_ROOT+"/register";
            console.log('registerData',registerData,api_url);
            await fetch(api_url,{
               method:"post",
               headers:{
                  'Authorization': `Bearer ${CONFIG.AUTH_TOKEN}`
               },
               body:registerData
            }).then(response => response.json())
            .then(response => {
               console.log(response.data);
               if(response.data.token){
                  localStorage.setItem('auth_user',response.data.token)
                  this.$router.push({ name: 'Password' });
               }else{
                  
                  this.error = (response.data.error) ? response.data.error : response.message;
               }
            })
            .catch(err => console.log('error',err));
                          
         }
      }
   }
</script>