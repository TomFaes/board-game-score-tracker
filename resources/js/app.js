/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('@suadelabs/vue3-multiselect/dist/vue3-multiselect.css');

import { createApp } from "vue";
import router from './services/router';
import store from '../js/services/store';

import globalLayout from '../js/components/global/globalLayout.vue';

//load de index page
import App from './pages/IndexPage/index.vue';
const app = createApp(App);

app.use(router);
app.use(store);

app.component("globalLayout", globalLayout);

app.mount('#app');
