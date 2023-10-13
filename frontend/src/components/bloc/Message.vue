<template>
	<div :class="[message.isMyMessage ? 'message-right' : 'message-left']">
		<div class="unite-msg">
			<div v-if="!message.isMyMessage" class="avatar"><span class="fa fa-user"></span></div>
			<div class="message-container">
				<div class="name-datetime">
					<div v-if="!message.isMyMessage" class="name">{{ message.messageOwner.username }}</div>
					<div class="datetime">{{ date }}</div>
				</div>
				<div class="message-content">
					{{ message.content }}
				</div>
			</div>
		</div>
	</div>
</template>
<script>
    export default {
        props : {
            message : Object
        },
        computed : {
            date(){
                var dateO = new Date(this.message.createdAt)
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
                return dateO.toLocaleString('fr-FR', options)
            }
        }
    }
</script>
