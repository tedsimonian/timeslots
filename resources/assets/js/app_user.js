
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
//window.Vue.config.devtools = true



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('user-profile', require('./components/user/UserProfile.vue'));
Vue.component('book-appointment', require('./components/user/BookAppointment.vue'));
Vue.component('user-calendar', require('./components/user/UserHomeCalendar.vue'));



const app = new Vue({
    el: '#app'
});
