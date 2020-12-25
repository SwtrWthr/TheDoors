/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
Vue.use(require('bootstrap-vue'));

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/Header.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('c-header', require('./components/Header.vue').default);
Vue.component('c-carousel', require('./components/Carousel.vue').default);
Vue.component('c-footer', require('./components/Footer.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import router from './router.js';
import store from './store'
import vuetify from './vuetify'
import VueMask from 'v-mask'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import {BootstrapVue, BootstrapVueIcons} from "bootstrap-vue";
import VueScrollTo from 'vue-scrollto'

Vue.use(BootstrapVueIcons);
Vue.use(BootstrapVue);
Vue.use(VueScrollTo)
Vue.use(VueMask)
const app = new Vue({
  el: '#app',
  router,
  store,
  vuetify
});
