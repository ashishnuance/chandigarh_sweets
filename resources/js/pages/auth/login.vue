<template>
    <div class="container">
      <div class="row">
         <div class="col-lg-12 d-flex align-items-center justify-content-center">
            <div class="PswrdPopup">
               <h2>Log In</h2>
               <div class=" PswrdCard">
                  <form action="" method="post">
                     <div class="BoxInput">
                        <label class="form-label">Email ID</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                     </div>
                     <div class="BoxInput">
                     <label class="form-label">Password</label>
                     <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                     <router-link to="/password">Forget Password</router-link>
                        
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

   <footer>
      <section>
         <div class="container-fluid  p-0">
            <div class="row g-0">
               <div class="col-lg-6 col-12">
                  <div class="infoBox">
                     <h5>Copyright © 2022 Chandigarh Sweets Ltd.</h5>
                  </div>
               </div>
               <div class="col-lg-6 col-12">
                  <div class="infoBox">
                     <h5>Developed By: Developer</h5>
                  </div>
               </div>
            </div>
         </div>
      </section>
      
   </footer>
</template>