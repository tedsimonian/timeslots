
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('add-user', require('./components/admin/AddUser.vue'));
Vue.component('edit-user', require('./components/admin/EditUser.vue'));
Vue.component('admin-profile', require('./components/admin/AdminProfile.vue'));
Vue.component('admin-rules', require('./components/admin/Rules.vue'));
Vue.component('admin-home', require('./components/admin/AdminHome.vue'));
Vue.component('edit-role', require('./components/admin/EditRole.vue'));

const app = new Vue({
    el: '#app'
});
