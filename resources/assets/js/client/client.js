/**
 * ProScholy.cz login.js file.
 *
 * It contains:
 * - Vue.js SPA with Vue Router 3
 * - Apollo GraphQL driver
 */

// Import Vue
window.Vue = require('vue');

// Client single page app component
Vue.component('client-spa', require('../client/ClientSpa.vue'));


// ! only for debugging purposes, delete soon
//Vue.component('song-view', require('../client/song/SongBox.vue'));

// GraphQL
import apolloProvider from "./apollo";
import VueApollo from 'vue-apollo';
Vue.use(VueApollo);

// Vue router
import router from './router'
import VueRouter from 'vue-router'
Vue.use(VueRouter);

/**
 * Create new Vue instance.
 */
const app = new Vue({
    el: '#app',
    apolloProvider,
    router
});
