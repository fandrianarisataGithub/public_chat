<template>
	<div class="page-chat">
        <nav class="custom-sidebarnav scrollbar">
            <ul>
                <Friend 
                    v-for="(user, index) in users"
                    :key="index"
                    :friendData = "user"
                />
            </ul>
        </nav>
        <router-view :key="$route.fullPath" name="b"></router-view>
    </div>
</template>
<script>
    import Cookies from 'js-cookie';
    import Friend from '../components/bloc/Friend.vue';
    import ChatContent from '../components/bloc/ChatContent.vue';
    import {mapGetters} from 'vuex';
    export default {
        data(){
            return {
                conversations : [],
                users : []
            }
        },
        components:{
            Friend,
            ChatContent
        },
        computed : {
            ...mapGetters['USERS'] // les destinataires
        },
        async mounted() {
            await this.$store.dispatch('GET_OTHER_USERS');
            this.users = this.$store.getters.USERS; 
            //console.log(this.conversations)        
        },
        created() {
            const authToken = Cookies.get('authToken');
            const currentUserId = Cookies.get('currentUserId');
        },  
    }
</script>
