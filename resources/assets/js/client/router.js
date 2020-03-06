import VueRouter from "vue-router";

import AboutSongBook from "./about/AboutSongBook";
import Search from "./search/Search";
import SongView from "./song/components/SongView";
import Song from "./song/Song";

/*
Client application Vue Router
 */

// Routes list
const routes = [
    {path: '/', component: Search},
    {path: '/search/:q', component: Search},

    {path: '/o-zpevniku', component: AboutSongBook},
    //  {path: '/pisen/:id/:slug', component: Song},

    // {path: '/muj-zpevnik', component: SongView}
];

// Client app Vue router instance.
export default new VueRouter({
    mode: 'history',
    routes
});
