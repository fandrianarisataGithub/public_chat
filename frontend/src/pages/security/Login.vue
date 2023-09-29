<template>
	<div class="page-security-login">
		<section class="wrapper">
			<div class="content">
				<header>
					<h1>Welcom back</h1>
				</header>
				<section>
					<!-- <div class="social-login">
						<button>
							<img
								src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"
								alt="google"
								width="20"
							/><span>Google</span>
						</button>
						<button>
							<img
								src="https://cdn.freebiesupply.com/logos/large/2x/facebook-2-logo-svg-vector.svg"
								alt="facebook"
								width="10"
							/><span>Facebook</span>
						</button>
					</div> -->
					<form  @submit.prevent="sendLogin" class="login-form">
						<div class="input-group">
							<label for="username">Email ou Username</label>
							<input
                                v-model="formData.login"
                                name="login"
								type="text"
								placeholder="Email ou Username"
								id="username"
							/>
						</div>
						<div class="input-group">
							<label for="password">Password</label>
							<input
                                v-model="formData.password"
                                name="password"
								type="password"
								placeholder="Password"
								id="password"
                                class=""
							/>
						</div>
                        <div v-if="isError" id="error" class="alert alert-danger mt-5">
                            {{errorMessage.error}}
                        </div>
						<div class="input-group for-buttons">
                            <button class="signin button-login" type="submit">Sign in</button>
                        </div>
                        <div class="redirection mt-3">
                            <router-link to="/register" class="button-login signup">Sign up</router-link>
                        </div>
					</form>
				</section>
				<footer>
					<a href="#" title="Forgot Password">Forgot Password</a>
				</footer>
			</div>
		</section>
	</div>
</template>
<script>
    import {backendUrl} from './../../assets/js/config';
    import Cookies from 'js-cookie';
    export default {
        name : "Login",
        computed: {
            isAuthenticated() {
                return this.$store.getters.isAuthenticated; 
            }
        },
        data(){
                return {
                    formData: {
                        login : '',
                        password : ''
                    },
                    isError : '',
                    errorMessage: '',
                    apiUrl : backendUrl
                }
        },
        created(){
            //this.$store.commit('test')
            const authToken = Cookies.get('authToken');
            const currentUserId = Cookies.get('currentUserId');
            //alert(authToken);
            const store = this.$store;
            if(authToken && currentUserId){
                store.commit('change', true);
            }
            console.log('user connecté: ' + this.isAuthenticated);
            if(this.$store.getters.isAuthenticated){
                // redirection to the homepage
                this.$router.push('/')
            }
        },
        methods:{
            sendLogin(){
                fetch(this.apiUrl + '/api/login',  {
                    method : 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                })
                .then(response => response.json())
                .then(data => {
                    //console.log(data)
                    if(data.token !== null && data.error === null){
                        Cookies.set('authToken', data.token, {expires: 7});
                        Cookies.set('currentUserId', data.userId, { expires: 7 });

                        this.$store.commit('change', true);
                        console.log('user authenticated successfully' + this.isAuthenticated);
                    }else{
                        this.isError = true;
                        this.errorMessage = data
                    }
                })
            }
        }
    }
</script>
