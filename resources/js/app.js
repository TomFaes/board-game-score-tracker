/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('vue-multiselect/dist/vue-multiselect.min.css');

import Vue from 'vue';
import router from './services/router';
import store from '../js/services/store';

import globalLayout from '../js/components/global/globalLayout.vue';
import globalInput from '../js/components/global/globalInput.vue'

window.Vue = require('vue');

//load de index page
Vue.component('index-page', require('./pages/IndexPage/index.vue').default);

//Global components
Vue.component('global-layout', globalLayout);
Vue.component('global-input', globalInput);

Vue.prototype.$bus = new Vue({});

const app = new Vue({
  router,
  store,
  el: '#app'
});
