import { backendUrl } from "../../config";
import Cookies from "js-cookie";

const conversationsStore = {
	state: {
		conversations: [],
	},
	mutations: {
		SET_CONVERSATIONS(state, payload) {
			state.conversations = payload;
		},
	},
	getters: {
		CONVERSATIONS: (state) => state.conversations
	},
	actions: {
		async GET_CONVERSATIONS({ commit }) {
			try {
				const currentUserId = Cookies.get("currentUserId");
				const response = await fetch(
					backendUrl + "/profile/conversation/",
					{
						method: "POST",
						headers: {
							"Content-Type": "application/json",
						},
						body: JSON.stringify({
                            currentUserId: currentUserId,
                        }),
					}
				);

				if (!response.ok) {
					throw new Error("Failed to fetch conversations");
				}

				const result = await response.json();
				commit("SET_CONVERSATIONS", result);
			} catch (error) {
				console.error("Error:", error);
			}
		},
	},
};

export default conversationsStore;
