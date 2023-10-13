import { backendUrl } from "../../config";
import Cookies from "js-cookie";
const conversationsStore = {
	state: {
		conversations: [],
        hubUrl: null,
        messages : []
	},
	mutations: {
		SET_CONVERSATIONS(state, payload) {
			state.conversations = payload;
		},
        SET_MESSAGES: (state, { conversationId, payload }) => {
            /**
             * console.log(conversationId)
                const messages = result.find(i => i.conversation.id === conversationId);
             */
            //console.log(conversationId, payload)
            const messages = [];
            for(var i=0; i<payload.length; i++){
                if(payload[i].conversation.id == conversationId){
                    messages.push(payload[i]);
                }
            }
            state.messages = messages;
        },
        SET_HUBURL: (state, payload) => state.hubUrl = payload,
        ADD_MESSAGE : (state,  {payload}) => {
            state.messages.push(payload);
        }
	},
	getters: {
		CONVERSATIONS: (state) => state.conversations,
        MESSAGES : (state) => state.messages
	},
	actions: {
		async GET_CONVERSATIONS({ commit }) {
			try {
				const currentUserId = Cookies.get("currentUserId");
				const response = await fetch(
					backendUrl + "/profile/conversation",
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
        GET_MESSAGES({commit}, conversationId){
            const currentUserId = Cookies.get("currentUserId");
            return fetch(`${backendUrl}/profile/message/${conversationId}`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        currentUserId: currentUserId,
                        conversationId : conversationId
                    }),
                }    
            )
            .then(result => result.json())
            .then((result) => {
                
                commit("SET_MESSAGES", {conversationId : conversationId, payload: result})
            });

        },
        POST_MESSAGE({commit},{ conversationId, content}){
            const currentUserId = Cookies.get("currentUserId");
            return fetch(`${backendUrl}/profile/message/new/${conversationId}`,
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        currentUserId: currentUserId,
                        content : content
                    }),
                }    
            )
            .then(result => result.json())
            .then((result) => {
                commit('ADD_MESSAGE', {payload : result});
            })
        }
	},
};

export default conversationsStore;
