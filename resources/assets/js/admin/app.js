
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('../bootstrap');

// /**
//  * Materialise.css
//  */
// require('materialize-css');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('songs-list', require('./pages/SongsList.vue'));
Vue.component('externals-list', require('./pages/ExternalsList.vue'));
Vue.component('files-list', require('./pages/FilesList.vue'));
Vue.component('authors-list', require('./pages/AuthorsList.vue'));
Vue.component('songbooks-list', require('./pages/SongbooksList.vue'));

Vue.component('author-edit', require('./pages/AuthorEdit.vue'));
Vue.component('external-edit', require('./pages/ExternalEdit.vue'));
Vue.component('song-lyric-edit', require('./pages/SongLyricEdit.vue'));
Vue.component('file-edit', require('./pages/FileEdit.vue'));
Vue.component('songbook-edit', require('./pages/SongbookEdit.vue'));

Vue.component('external-view', require('../components/ExternalView.vue'));


import { ApolloClient } from 'apollo-client'
import { createHttpLink } from 'apollo-link-http'
import { InMemoryCache } from 'apollo-cache-inmemory'
import { ApolloLink } from 'apollo-link'

// let web_url = 'http://localhost:8000/graphql';

// if (mix.inProduction()) {
//   web_url = 'https://zpevnik.proscholy.cz/graphql';
// } else {
//   web_url = 'http://localhost:8000/graphql';
// }

var base_url = document.querySelector('#baseUrl').getAttribute('value');
var user_token = document.querySelector('#userToken').getAttribute('value');

const authMiddleware = new ApolloLink((operation, forward) => {
  // add the authorization to the headers
  operation.setContext({
    headers: {
      authorization: `Bearer ${user_token}`
    }
  }) 

  return forward(operation)
});

// HTTP connexion to the API
const httpLink = createHttpLink({
  // You should use an absolute URL here
  uri: base_url + '/graphql',
})

const cache = new InMemoryCache()

// Create the apollo client
const apolloClient = new ApolloClient({
  link: authMiddleware.concat(httpLink),
  cache,
})

import VueApollo from 'vue-apollo'
Vue.use(VueApollo)

const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
})

import Vuetify from 'vuetify'
Vue.use(Vuetify)

import Notifications from 'vue-notification'
Vue.use(Notifications)

import VeeValidate from 'vee-validate'
Vue.use(VeeValidate)


const app = new Vue({
    el: '#app',
    apolloProvider,
    // render: h => h(App)
});
