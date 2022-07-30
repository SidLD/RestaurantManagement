require('./bootstrap');


window.Vue = require('vue').default;

import Vuetify from '../plugins/vuetify';
import router from './router';
import Vue from 'vue';
import '@mdi/font/css/materialdesignicons.css';
import '@fortawesome/fontawesome-free/css/all.css';
import DatetimePicker from 'vuetify-datetime-picker'

Vue.component('dashboard-container', require('./Main.vue').default);
Vue.component('login-container', require('./components/pages/Login.vue').default);

Vue.use(DatetimePicker);
const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
    router,
});
