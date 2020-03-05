import VueRouter from "vue-router";

import AboutSongBook from "./about/AboutSongBook";
import Song from "./song/Song";
import Search from "./search/Search";

/*
Client application Vue Router
 */

// Routes list
const routes = [
    {path: '/', component: Search},
    {path: '/o-zpevniku', component: AboutSongBook},
    {path: '/song', component: Song}
];

// Client app Vue router instance.
export default new VueRouter({routes});
