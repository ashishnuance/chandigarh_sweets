<template>
   <section class="LoginSection">
      <div class="container">
         <div class="row">
            <div class="col-lg-12 d-flex align-items-center justify-content-center">
               <div class="PswrdPopup">
                  <h2>Log In</h2>
                  <div class=" PswrdCard">
                     <form method="post" @submit.prevent="login">
                     <div class="BoxInput">
                        <label class="form-label">Email ID</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" v-model="email">
                     </div>
                     <div class="BoxInput">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="exampleInputpassword" aria-describedby="emailHelp" v-model="password">
                     </div>
                     
                     <div class="PswrdBtn">
                        
                        <button type="submit">Submit</button>
                        <router-link to="/register">Sign In</router-link>
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
   export default {
      data(){
         return [];
      }, 
      computed:{
         
         isLogin() {
            return this.$store.state.isLogin;
         },
      },
     
      methods:{
         async login(){
            let app = this;
            let api_url = CONFIG.API_URL_ROOT+'/login';
            console.log(app.email,app.password)
            let loginFormData = new FormData();
            loginFormData.append('email',app.email);
            loginFormData.append('password',app.password);
            await fetch(api_url,{
               method:'post',
               body:loginFormData
            }).then(response => response.json())
            .then(response => {
               console.log('Login',response.data)
               if(response.data.token){
                  // localStorage.setItem('auth_user',JSON.stringify(response.data));
                  // this.$store.commit('updateLoginStatus', true);
                  // this.$store.commit('setToken', response.data.token);
                  this.$store.dispatch('setToken',response.data.token)
                  this.$store.dispatch('setAuth',true)
                  this.$router.push({name:'Home'});
               }else{
                  alert(response.message);
               }
            }).catch(err=>console.log('login error',err))
         }
      }
   }
</script>