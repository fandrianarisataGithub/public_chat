<template>
	<div class="messages">
		<div class="discussion">
			<div v-for="(message, indexM) in MESSAGES" :key="indexM">
                <Message :message="message" />
            </div>
		</div>
		<InputMessage />
	</div>
</template>
<script>
import InputMessage from "./InputMessage.vue";
import Message from "./Message.vue";
export default {
	components: {
		InputMessage,
		Message,
	},
    computed: {
        MESSAGES(){
            //console.log(this.$store.getters.MESSAGES)
            return this.$store.getters.MESSAGES;
        }
    },
    mounted(){
        this.$store.dispatch('GET_MESSAGES', this.$route.params.id)
    }
};
</script>
