import { createStore } from 'vuex';

export default createStore({
    state : {
        authenticated: false,
    },
    mutations : {
        change(state, value) {
            state.authenticated = value;
        },
        test(){
            alert('kkkkkk')
        }
    },
    getters : {
        isAuthenticated(state) {
            if (state.authenticated === 'true' || state.authenticated === true) {
                return true;
            }
            return false;
        },
    },
    actions : {}
})