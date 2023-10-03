import Cookies from "js-cookie";
import { backendUrl } from "../../config";
const friendsStore = {
    state : {
        friends : [],
    },
    mutations : {
        SET_FRIENDS (state, payload){
            state.friends = payload
        }
    },
    getters : {
        FRIENDS : (state) => state.friends
    },
    actions : {
        async GET_FRIENDS ({commit}){
            try {
                const currentUserId = Cookies.get("currentUserId");
                const response = await fetch(
					backendUrl + "/profile/users/" + currentUserId,
					{
						method: "GET",
						headers: {
							"Content-Type": "application/json",
						},
					}
				);

				if (!response.ok) {
					throw new Error("Failed to fetch friends");
				}

				const result = await response.json();
				commit("SET_FRIENDS", result);

            } catch (error) {
                console.error("Error:", error);
            }
        }
    }

}
export default friendsStore;