window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Chord from 'Public/pages/Song/Chord.vue';
import SongPartTag from 'Public/pages/Song/SongPartTag.vue';
import ExternalView from 'Public/components/ExternalView.vue';
import DarkModeButton from 'Public/components/DarkModeButton.vue';
import Search from 'Public/pages/Search/Search.vue';
import SongView from 'Public/pages/Song/SongView.vue';
import Recaptcha from 'Public/pages/Login/Recaptcha.vue';
import UserAccount from 'Public/pages/UserAccount/UserAccount.vue';

Vue.component('chord', Chord);
Vue.component('song-part-tag', SongPartTag);
Vue.component('external-view', ExternalView);
Vue.component('dark-mode-button', DarkModeButton);
Vue.component('search', Search);
Vue.component('song-view', SongView);
Vue.component('recaptcha', Recaptcha);
Vue.component('user-account', UserAccount);

// firebase firestore plugin for vue
// import { firestorePlugin } from 'vuefire'
// Vue.use(firestorePlugin)

import { ApolloClient } from 'apollo-client';
import { createHttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';
import { ApolloLink } from 'apollo-link';
import VueApollo from 'vue-apollo';

var base_url = document.querySelector('#baseUrl').getAttribute('value');
var user_token = document.querySelector('#userToken').getAttribute('value');

// HTTP connexion to the API
const httpLink = createHttpLink({
    // You should use an absolute URL here
    uri: base_url + '/graphql'
});

const cache = new InMemoryCache();

const authMiddleware = new ApolloLink((operation, forward) => {
    // add the authorization to the headers
    operation.setContext({
        headers: {
            authorization: `Bearer ${user_token}`
        }
    });

    return forward(operation);
});

// Create the apollo client
const apolloClient = new ApolloClient({
    link: authMiddleware.concat(httpLink),
    cache
});

Vue.use(VueApollo);

const apolloProvider = new VueApollo({
    defaultClient: apolloClient
});

Vue.directive('auth', function(el, binding, vnode) {
    if (user_token == '') {
        el.style.display = 'none';
    }
});

const app = new Vue({
    el: '#app',
    apolloProvider
});
