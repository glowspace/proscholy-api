
window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */ 

Vue.component('chord', require('Public/pages/Song/Chord.vue'));
Vue.component('external-view', require('Public/components/ExternalView.vue'));
Vue.component('dark-mode-button', require('Public/components/DarkModeButton.vue'));
Vue.component('search', require('Public/pages/Search/Search.vue'));
Vue.component('song-view', require('Public/pages/Song/SongView.vue'));


import { ApolloClient } from 'apollo-client';
import { createHttpLink } from 'apollo-link-http';
import { InMemoryCache } from 'apollo-cache-inmemory';
import { persistCache, CachePersistor } from 'apollo-cache-persist';
import VueApollo from 'vue-apollo';

var base_url = document.querySelector('#baseUrl').getAttribute('value');

// HTTP connexion to the API
const httpLink = createHttpLink({
  // You should use an absolute URL here
  uri: base_url + '/graphql',
})

const cache = new InMemoryCache();

// Set up cache persistence.
// window.cachePersistor = new CachePersistor({
//   cache,
//   storage: window.sessionStorage,
// });

// persistCache({
//   cache,
//   storage: window.sessionStorage
// }).then(function() {
  
//     // Create the apollo client
//     const apolloClient = new ApolloClient({
//       link: httpLink,
//       cache,
//     })
    
//     Vue.use(VueApollo)
  
//     const apolloProvider = new VueApollo({
//         defaultClient: apolloClient,
//     })
  
//     const app = new Vue({
//       el: '#app',
//       apolloProvider
//     });
// });

// Create the apollo client
const apolloClient = new ApolloClient({
  link: httpLink,
  cache,
})

Vue.use(VueApollo)

const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
})

const app = new Vue({
  el: '#app',
  apolloProvider
});