/**
 * ProScholy.cz app.js file.
 *
 * It contains:
 * - Vue.js SPA with Vue Router 3
 * - Apollo GraphQL driver
 */

// Imports
window.Vue = require('vue');

// Client single page app
Vue.component('client-spa', require('../client/ClientSpa.vue'));


// TODO: add laravel automatic recursive import
Vue.component('chord', require('../pages/Song/Chord.vue'));


import {ApolloClient} from 'apollo-client';
import {createHttpLink} from 'apollo-link-http';
import {InMemoryCache} from 'apollo-cache-inmemory';
import {persistCache, CachePersistor} from 'apollo-cache-persist';
import {ApolloLink} from 'apollo-link'
import VueApollo from 'vue-apollo';
import {auth} from 'Public/helpers/firebase_auth';
import VueRouter from 'vue-router'


// GraphQL
var base_url = document.querySelector('#baseUrl').getAttribute('value');

// HTTP connection to the API
const httpLink = createHttpLink({
    // You should use an absolute URL here
    uri: base_url + '/graphql',
});

const cache = new InMemoryCache();

// // Set up cache persistence.
// window.cachePersistor = new CachePersistor({
//   cache,
//   storage: window.sessionStorage,
// });

// persistCache({
//   cache,
//   storage: window.sessionStorage
// }).then(function() {

// Create the apollo client
const apolloClient = new ApolloClient({
    link: httpLink,
    cache,
});

// Vue plugins
Vue.use(VueApollo);
Vue.use(VueRouter);

const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
});

const app = new Vue({
    el: '#app',
    apolloProvider,
    VueRouter
});
