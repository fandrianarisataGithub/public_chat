<template>
	<div class="page-chat">
        <nav class="custom-sidebarnav scrollbar">
            <ul>
                <Friend 
                    v-for="(conversation, index) in conversations"
                    :key="index"
                    :friendData = "conversation"
                />
            </ul>
        </nav>
        <div class="messages">
            <div class="discussion">
                <div class="message-left">
                    <div class="unite-msg">
                        <div class="avatar"><span class="fa fa-user"></span></div>
                        <div class="message-container">
                            <div class="name-datetime">
                                <div class="name">Son nom</div>
                                <div class="datetime">14:12</div>
                            </div>
                            <div class="message-content">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptate, cupiditate.
                            </div>
                        </div>
                    </div>
                    <div class="unite-msg">
                        <div class="avatar"><span class="fa fa-user"></span></div>
                        <div class="message-container">
                            <div class="name-datetime">
                                <div class="name">Son nom</div>
                                <div class="datetime">14:12</div>
                            </div>
                            <div class="message-content">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptate, cupiditate.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="message-right">
                    <div class="unite-msg">
                        <div class="message-container">
                            <div class="name-datetime">
                                <div class="datetime">14:12</div>
                            </div>
                            <div class="message-content">
                                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptate, cupiditate.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="input-message">
                <form action="">
                    <input type="text">
                    <button>
                        <span class="fa fa-send-o"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import Cookies from 'js-cookie';
    import Friend from '../components/bloc/Friend.vue';
    import {mapGetters} from 'vuex';
    export default {
        data(){
            return {
                conversations : [],
                users : []
            }
        },
        components:{
            Friend
        },
        computed : {
            ...mapGetters['CONVERSATIONS']
        },
        async mounted() {
            await this.$store.dispatch('GET_CONVERSATIONS');
            this.conversations = this.$store.getters.CONVERSATIONS; 
            //console.log(this.conversations)        
        },
        created() {
            const authToken = Cookies.get('authToken');
            const currentUserId = Cookies.get('currentUserId');
        },  
    }
</script>
