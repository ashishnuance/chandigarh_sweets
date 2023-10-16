<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 d-flex align-items-center justify-content-center">
                <div class="PswrdPopup">
                    <h2>Password</h2>
                    <div class=" PswrdCard">
                        <form method="post" @submit.prevent="generatePassword">
                            <div class="BoxInput">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" v-model="password">
                            </div>
                            <div class="BoxInput">
                            <label class="form-label">Confirm Password</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" v-model="password_confirmation">
                            </div>
                            <div class="PswrdBtn">
                            <button type="submit" class="btn btn-danger">Generate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>
<script>
    import CONFIG from '../../config';
    export default {
        data(){
            return [];
        },
        methods:{
            async generatePassword(){
                let app = this;
                let api_url = CONFIG.API_URL_ROOT+'/generate-password';
                let auth_user = localStorage.getItem('auth_user');
                console.log(auth_user.user_id)
                let paswordFormData = new FormData();
                paswordFormData.append('password',app.password);
                paswordFormData.append('password_confirmation',app.password_confirmation);
                await fetch(api_url,{
                    method:'post',
                    headers:{
                        'Authorization': `Bearer ${auth_user.token}`
                    },
                    body:paswordFormData
                }).then(response => response.json())
                .then(response => {
                    console.log(response);
                    if(response.success){
                        this.$router.push({name:'Login'});
                    }else{
                        alert(response.message);
                        this.$router.push({name:'Register'});
                    }
                }).catch(err => console.log('error password',err))
            }

        }
    }
</script>