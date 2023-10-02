<template>
    <div>
        <Header></Header>
        
        <router-view></router-view>

        <Footer></Footer>
    </div>
</template>
<script>
    import Footer from './../includes/footer.vue';
    import Header from '../includes/header.vue';
    import CONFIG from '../config';

    export default {
        

        components : {
            Footer,
            Header
        }
    }
</script>