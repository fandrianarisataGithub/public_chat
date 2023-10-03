<template>
	<nav class="layout-header navbar navbar-expand-lg bg-transparent">
		<div class="container-fluid">
			<a class="navbar-brand fc-white" href="#">Fenitra <span class="my-status"></span></a>
			<button
				class="navbar-toggler"
				type="button"
				data-bs-toggle="collapse"
				data-bs-target="#navbarSupportedContent"
				aria-controls="navbarSupportedContent"
				aria-expanded="false"
				aria-label="Toggle navigation"
			>
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto ml-auto mb-2 mb-lg-0 center-ul">
					<li class="nav-item active">
                        <router-link 
                            to="/"
                            class="nav-link fc-white active" 
                            aria-current="page"
                        >
                            Home
                        </router-link>
					</li>
					<li class="nav-item">
						<router-link 
                            to="/chat"
                            class="nav-link fc-white active" 
                            aria-current="page"
                        >
                            Chat
                        </router-link>
					</li>
                    <li class="nav-item">
						<router-link 
                            to="/inbox"
                            class="nav-link fc-white active" 
                            aria-current="page"
                        >
                            Inbox
                        </router-link>
					</li>
                    <li class="nav-item">
						<a href="#" @click.prevent="logout" class="nav-link fc-white active" 
                            aria-current="page">Logout</a>
					</li>
					<!-- <li class="nav-item dropdown">
						<a
							class="nav-link dropdown-toggle"
							href="#"
							role="button"
							data-bs-toggle="dropdown"
							aria-expanded="false"
						>
							Dropdown
						</a>
						<ul class="dropdown-menu">
							<li>
								<a class="dropdown-item" href="#">Action</a>
							</li>
							<li>
								<a class="dropdown-item" href="#"
									>Another action</a
								>
							</li>
							<li><hr class="dropdown-divider" /></li>
							<li>
								<a class="dropdown-item" href="#"
									>Something else here</a
								>
							</li>
						</ul>
					</li>
					<li class="nav-item">
						<a class="nav-link disabled">Disabled</a>
					</li> -->
				</ul>
				<form class="d-flex search-form" role="search">
                    <div v-if="resultSearch.length > 0 && searchQuery" class="response-search">
                        <ul>
                            <li v-for="(user, index) in resultSearch" :key="index">
                                <a href="#" :user-id="user.id">{{ user.username }}</a>
                            </li>
                        </ul>
                    </div>
					<input
                        v-model="searchQuery"
						class="form-control me-2 custum-search-input"
						type="search"
						placeholder="Search"
						aria-label="Search"
                        @input="search"
					/>
					<button @click.prevent="search" class="btn btn-outline-success custum-search-button" type="submit">
						Search
					</button>
				</form>
			</div>
		</div>
	</nav>
</template>
<script>
    import Cookies from 'js-cookie';
    import {mapGetters} from 'vuex';
    export default {
        data(){
            return {
                users : [],
                resultSearch : [],
                searchQuery : ''
            }
        },
        computed: {
            ...mapGetters['USERS']
        },
        methods: {
            logout(){
                const store = this.$store;
                store.commit('change', false); 

                Cookies.remove('authToken');
                Cookies.remove('currentUserId');
                // redierc to login 
                this.$router.push('/login')
            },
            search() {
                console.log(this.searchQuery)
                this.resultSearch = this.users.filter(user => {
                    return user.username.toLowerCase().includes(this.searchQuery.toLowerCase());
                });
                //*  */console.log(this.resultSearch)
            }
        },
        async mounted(){
            await this.$store.dispatch('GET_USERS')
            this.users = this.$store.getters.USERS;
        }
    }
</script>
