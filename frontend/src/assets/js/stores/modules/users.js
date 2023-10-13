import Cookies from 'js-cookie';
import { backendUrl } from "../../config";
const userStore = {
    state : {
        users : []
    },
    mutations : {
        SET_USERS : (state, payload) => {
            state.users = payload;
        }
    },
    getters : {
        USERS  : (state) => state.users
    },
    actions : {
         async GET_OTHER_USERS({commit}){
            // request with fetch
            try {
                const currentUserId = Cookies.get('currentUserId');
                let url = backendUrl + '/profile/users';
                const response  = await fetch(url, 
                    {
						method: "POST",
						headers: {
							"Content-Type": "application/json",
						},
                        body : JSON.stringify({
                            currentUserId: currentUserId,
                        }),
					}
                )
                if (!response.ok) {
					throw new Error("Failed to fetch conversations");
				}
                const result = await response.json();
                //console.log(result);
				commit("SET_USERS", result);

            } catch (error) {
                console.error('error request ' + error)
            }
         }
    }
}

export default userStore;