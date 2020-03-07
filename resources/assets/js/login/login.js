require('./bootstrap');

// Import Vue
window.Vue = require('vue');

Vue.component('recaptcha', require('./Recaptcha'));
Vue.component('dark-mode-button', require('../components/DarkModeButton'));

const app = new Vue({
    el: '#app',
});
