
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


Vue.component('songs-list', require('./components/SongsList.vue'));

import { ApolloClient } from 'apollo-client'
import { createHttpLink } from 'apollo-link-http'
import { InMemoryCache } from 'apollo-cache-inmemory'

let web_url = 'http://localhost:8000/graphql';

// if (mix.inProduction()) {
//   web_url = 'https://zpevnik.proscholy.cz/graphql';
// } else {
//   web_url = 'http://localhost:8000/graphql';
// }

// HTTP connexion to the API
const httpLink = createHttpLink({
  // You should use an absolute URL here
  uri: web_url,
})

const cache = new InMemoryCache()

// Create the apollo client
const apolloClient = new ApolloClient({
  link: httpLink,
  cache,
})

import VueApollo from 'vue-apollo'
Vue.use(VueApollo)

const apolloProvider = new VueApollo({
    defaultClient: apolloClient,
})

import Vuetify, {
  VApp, // required
  VNavigationDrawer,
  VDataTable
} from 'vuetify/lib'

Vue.use(Vuetify, {
  components: {
    VApp,
    VNavigationDrawer,
    VDataTable
  }
})

// import 'vuetify/dist/vuetify.min.css'

const app = new Vue({
    el: '#app',
    // apolloProvider
});
