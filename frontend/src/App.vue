<template lang="">
    <div>
        <Header v-show="isAuthenticated"/>
        <router-view :key="$route.fullPath"></router-view>
    </div>
</template>
<script>
    import Header from './components/layout/Header.vue'
    export default {
        computed: {
            isAuthenticated() {
                return this.$store.getters.ISAUTHISUSERAUTHENTICATED; 
            }
        },
        components:{
            Header
        }
    }
</script>