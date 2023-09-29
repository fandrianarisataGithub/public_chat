<template lang="">
    <div>
        <Header v-show="isAuthenticated"/>
        <router-view></router-view>
    </div>
</template>
<script>
    import Header from './components/layout/Header.vue'
    export default {
        computed: {
            isAuthenticated() {
                return this.$store.getters.isAuthenticated; 
            }
        },
        components:{
            Header
        }
    }
</script>