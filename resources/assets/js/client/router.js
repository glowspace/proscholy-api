import VueRouter from "vue-router";

import Search from "./search/Search";
import AboutSongBook from "./about/AboutSongBook";
import Song from "./song/Song";
import Login from "./account/Login";

/*
Client application Vue Router
 */

// Routes list
const routes = [
    {path: '/', component: Search},
    {path: '/search', component: Search},

    {path: '/o-zpevniku', component: AboutSongBook},
    {path: '/pisen/:id/:slug', component: Song},

    {path: '/muj-ucet', component: Login}
];

// Client app Vue router instance.
export default new VueRouter({
    mode: 'history',
    routes
});
