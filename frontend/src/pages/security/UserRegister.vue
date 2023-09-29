<template>
	<div class="page-security-user-register page-security-login">
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
					<form @submit.prevent="validateForm" action="" class="login-form">
						<div class="input-group">
							<label for="username">Username</label>
							<input
								type="text"
								placeholder="Username or Email"
								id="username"
                                required
							/>
						</div>
                        <div class="input-group">
							<label for="username">Email</label>
							<input
								type="email"
								placeholder="Username or Email"
								id="username"
                                required
							/>
						</div>
						<div class="input-group">
							<label for="password">Password</label>
							<input
								type="password"
								placeholder="Password"
								id="password"
                                required
							/>
						</div>
                        <div class="input-group">
							<label for="password">Confirm password</label>
							<input
								type="password"
								placeholder="Password"
								id="cpassword"
                                required
							/>
						</div>
						<div class="input-group for-buttons">
                            <button class="signin button-login" type="submit">Sign up</button>
                        </div>
                        <div class="redirection mt-3">
                            <router-link to="/login" class="button-login signup">Sign in</router-link>
                        </div>
					</form>
				</section>
			</div>
		</section>
	</div>
</template>
<script>
export default {
  data() {
    return {
      username: '',
      email: '',
      password: '',
      cpassword: ''
    };
  },
  methods: {
    validateForm() {
      if (!this.username || !this.email || !this.password || !this.cpassword) {
        alert("Veuillez remplir tous les champs du formulaire.");
        return;
      }
      
      if (!this.isValidEmail(this.email)) {
        alert("Veuillez fournir une adresse e-mail valide.");
        return;
      }

      if (this.password !== this.cpassword) {
        alert("Les mots de passe ne correspondent pas.");
        return;
      }
      this.sendForm();
    },
    isValidEmail(email) {
      // Exemple de validation basique d'une adresse e-mail
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    },
    sendForm() {
      alert("Formulaire soumis avec succès !");
      // Réinitialise les champs du formulaire si nécessaire
      this.username = '';
      this.email = '';
      this.password = '';
      this.cpassword = '';
    }
  }
};
</script>
