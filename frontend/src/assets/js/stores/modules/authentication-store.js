const authModuleInStore = {
	state: {
		authenticated: false,
	},
	mutations: {
		change(state, value) {
			state.authenticated = value;
		},
	},
	getters: {
        ISAUTHISUSERAUTHENTICATED: (state) => state.authenticated,
	},
	actions: {},
};

export default authModuleInStore;
