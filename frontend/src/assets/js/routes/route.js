import Home from "../../../pages/Home.vue";
import Chat from "../../../pages/Chat.vue";
import Inbox from "../../../pages/Inbox.vue";
import Login from "../../../pages/security/Login.vue";
import UserRegister from "../../../pages/security/UserRegister.vue";
import ChatContent from "./../../../components/bloc/ChatContent.vue";
import ChatContentBlank from "./../../../components/bloc/ChatContentBlank.vue";
const routes = [
	{ 
        path: "/", 
        components: {
            a : Home
        }, 
        meta: { requiresAuth: true } 
    },
	{
        name: 'chat',
		path: "/chat",
		components: {
            a: Chat
        },
		meta: { requiresAuth: true },
        children :[
            {
                name : 'chat_conversation',
                path : 'conversation/:id',
                components : {
                
                    b : ChatContent
                }
            },
            {
                name : 'chat_blank',
                path : 'blank',
                components : {
                
                    b : ChatContentBlank
                }
            }
        ]

	},
	{ path: "/inbox", components: {
        a : Inbox
    }, meta: { requiresAuth: true } },
	{ path: "/login", components: {
        a : Login
    }, props: true },
	{ path: "/register", components: {
        a : UserRegister
    },  props: true },
];

export default routes;
