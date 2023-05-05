/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue';

import Recaptcha from 'Public/pages/Login/Recaptcha.vue';

// import Recaptcha from 'Public/pages/Login/Recaptcha.vue';

import SongsList from 'Admin/pages/list/SongsList.vue';
import ExternalsList from 'Admin/pages/list/ExternalsList.vue';
import AuthorsList from 'Admin/pages/list/AuthorsList.vue';
import TagsList from 'Admin/pages/list/TagsList.vue';
import SongbooksList from 'Admin/pages/list/SongbooksList.vue';
import NewsItemsList from 'Admin/pages/list/NewsItemsList.vue';
import AuthorEdit from 'Admin/pages/edit/AuthorEdit.vue';
import ExternalEdit from 'Admin/pages/edit/ExternalEdit.vue';
import TagEdit from 'Admin/pages/edit/TagEdit.vue';
import SongLyricEdit from 'Admin/pages/edit/SongLyricEdit.vue';
import SongbookEdit from 'Admin/pages/edit/SongbookEdit.vue';
import NewsItemEdit from 'Admin/pages/edit/NewsItemEdit.vue';
import UserStats from 'Admin/components/UserStats.vue';
import ProgressRow from 'Admin/components/ProgressRow.vue';

Vue.component('recaptcha', Recaptcha);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('songs-list', SongsList);
Vue.component('externals-list', ExternalsList);
Vue.component('authors-list', AuthorsList);
Vue.component('tags-list', TagsList);
Vue.component('songbooks-list', SongbooksList);
Vue.component('news-items-list', NewsItemsList);

Vue.component('author-edit', AuthorEdit);
Vue.component('external-edit', ExternalEdit);
Vue.component('tag-edit', TagEdit);
Vue.component('song-lyric-edit', SongLyricEdit);
Vue.component('songbook-edit', SongbookEdit);
Vue.component('news-item-edit', NewsItemEdit);

Vue.component('user-stats', UserStats);
Vue.component('progress-row', ProgressRow);

import { ApolloClient } from 'apollo-client';
import { createUploadLink } from 'apollo-upload-client';
import { InMemoryCache } from 'apollo-cache-inmemory';
import { ApolloLink } from 'apollo-link';

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
    });

    return forward(operation);
});

// HTTP connexion to the API
const httpLink = createUploadLink({
    // You should use an absolute URL here
    uri: base_url + '/graphql'
});

// clear the session storage used for caching GrapQL queries on the public frontend
// not doing so causes frontend not updating after admin edits
window.sessionStorage.clear();

const cache = new InMemoryCache();

// Create the apollo client
const apolloClient = new ApolloClient({
    link: authMiddleware.concat(httpLink),
    cache
});

import VueApollo from 'vue-apollo';
Vue.use(VueApollo);

const apolloProvider = new VueApollo({
    defaultClient: apolloClient
});

import Vuetify from 'vuetify';

Vue.use(Vuetify, {
    theme: {
        primary: '#3f51b5',
        secondary: '#00bcd4',
        accent: '#3f51b5',
        error: '#f44336',
        warning: '#ff9800',
        info: '#2196f3',
        success: '#4caf50'
    },
    lang: {
        locales: {
            cs: {
                dataIterator: {
                    rowsPerPageAll: '∞',
                    pageText: '{0}–{1} z {2}',
                    noResultsText:
                        'Nic nebylo nalezeno. Zkuste zkontrolovat vyhledávaný řetězec.'
                },
                dataTable: {
                    rowsPerPageText: 'Počet řádků na stránce:'
                },
                noDataText: 'Data nejsou k dispozici.'
            }
        },
        current: 'cs'
    }
});

import Notifications from 'vue-notification';
Vue.use(Notifications);

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

const app = new Vue({
    el: '#app',
    apolloProvider,
    data: {
        dark: false
    }
    // render: h => h(App)
});

window.VueApp = app;
