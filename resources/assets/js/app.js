
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

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

Vue.component('chord', require('./components/Chord.vue'));
Vue.component('transposition', require('./components/Transposition.vue'));
Vue.component('font-sizer', require('./components/FontSizer.vue'));


var base_url = document.querySelector('#baseUrl').getAttribute('value');

// HTTP connexion to the API
const httpLink = createHttpLink({
  // You should use an absolute URL here
  uri: base_url + '/graphql',
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
//   VNavigationDrawer,
//   VDataTable,
//   VContainer,
//   VLayout,
//   VFlex,
//   VCard,
//   VCardText,
//   VTextField
} from 'vuetify/lib'

Vue.use(Vuetify, {
  components: {
    VApp,
    // VNavigationDrawer,
    // VDataTable,
    VContainer,
    VLayout,
    VFlex,
    VCard,
    VCardText,
    // VTextField
  }
})

const app = new Vue({
    el: '#app',
    apolloProvider
});