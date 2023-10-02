<template>
    
    <div class="oblique">
      <div class="main-block-oblique skew-block">
         <div class="skew-block-repeat" v-for="setting_value in settings.home_banner">
            <a :href="setting_value.url">
                <div class="oblique-inner">
                    <div class="image-wrapper">
                        <div class="main-image">
                        <img class="image-img" v-bind:src="setting_value.image" alt="">
                     </div>
                  </div>
               </div>
               <div class="oblique-caption caption-top QuotOrder">
                  <h2>{{setting_value.text}}</h2>
               </div>
            </a>
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