<template>
    <li class="bloc-conversation">
        <router-link to="/" class="link">
            {{friendData.username}} 
            <span class="last-message"></span> 
            <!--span class="croq">...</span-->
        </router-link>
        <!--a href="#" class="amis">
            <span class="icon">{{ friendData.demand_status }}</span>
        </a>
        <a href="#" class="connected isconnected">
        </a-->
    </li>
</template>
<script>
    export default {
        props : {
            friendData : Object
        }
    }
</script>