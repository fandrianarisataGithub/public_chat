<template>
    <li class="bloc-conversation">
        <a href="#" :conv-id="friendData.convId" class="link">
            {{friendData.otherUserUsernme}} 
            <span class="last-message">{{ friendData.content }}</span> 
            <span class="croq">...</span>
        </a>
        <a href="#" class="amis">
            <span class="icon">{{ friendData.demand_status }}</span>
        </a>
        <a href="#" class="connected isconnected">
        </a>
    </li>
</template>
<script>
    export default {
        props : ['friendData']
    }
</script>