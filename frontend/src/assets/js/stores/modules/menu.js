const menuStore = {
    state : {
        activeMenu : null
    },
    getters : {
        ACTIVE_MENU : (state) => state.activeMenu,
        IS_ACTIVE_MENU(state){
            return function (route){
                return state.activeMenu === route;
            };
        }
    },
    mutations : {
        SET_ACTIVE_MENU(state, payload){
            state.activeMenu = payload
        }
    },
    actions : {
        SET_ACTIVE_MENU({commit}, route){
            commit('SET_ACTIVE_MENU', route)
        }
    }
}
export default menuStore;