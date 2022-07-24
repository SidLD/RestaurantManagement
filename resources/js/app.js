require('./bootstrap');


window.Vue = require('vue').default;

import Vuetify from '../plugins/vuetify';
import router from './router';
import Vue from 'vue';
import '@mdi/font/css/materialdesignicons.css';
import '@fortawesome/fontawesome-free/css/all.css';

Vue.component('main-container', require('./Main.vue').default);

const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
    router
});
