<template>
    <div class="page-home">
        <h1>Home page</h1>
    </div>
</template>
<script>
    import Cookies from 'js-cookie';
    export default {
        created() {
            const authToken = Cookies.get('authToken');
            const currentUserId = Cookies.get('currentUserId');
        },  
    }
</script>