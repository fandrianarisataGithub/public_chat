<template>
	<div class="input-message">
		<form action="">
			<input v-model="content" type="text" />
			<button @click.prevent="sendMessage">
				<span class="fa fa-send-o"></span>
			</button>
		</form>
	</div>
</template>
<script>
export default {
    data(){
        return {
            content : null,
        }
    },
    methods: {
        sendMessage(){
            this.$store.dispatch('POST_MESSAGE', {
                conversationId : this.$route.params.id,
                content : this.content
            })

        }
    },

}
</script>
