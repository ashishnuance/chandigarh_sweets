<template>
   <div class="container">
    <div class="row">
        <div class="col-lg-12 d-flex align-items-center justify-content-center">
            <div class="oblique">
                <div class="thankyouImg">
                <img src="http://localhost:8000/img/thankyou.jpg" alt="">
            </div>
      <h2>Your order is placed</h2>
      <router-link to="/" class="obliqueBtn">Continue Shoping</router-link>
   </div>
        </div>
    </div>
   </div>
    
   
   
</template>
<script>   
    import CONFIG from '../config';
    export default {
        data() {
            return {
                settings: [],
                pagination: {}
            };
        },

        created() {
            this.getAppSettings();
        },

        methods: {
            getAppSettings() {
                let api_url = CONFIG.API_URL_ROOT+'/app-settings';
                fetch(api_url)
                    .then(response => response.json())
                    .then(response => {
                        this.settings = response.data;
                        console.log('response',response.data.home_banner[0].image);
                    })
                    .catch(err => console.log(err));
            }
        }
    };
</script>
