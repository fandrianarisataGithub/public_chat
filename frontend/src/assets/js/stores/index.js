import { createStore } from 'vuex';

import authModuleInStore from './modules/authentication-store.js';
import conversationStore from './modules/conversation.js';
import friendsStore from './modules/friends.js';
import menuStore from './modules/menu.js';
import userStore from './modules/users.js';

const store = createStore({
    modules: {
        menuStore,
        authModuleInStore,
        conversationStore,
        friendsStore,
        userStore
    }
});

export default store;