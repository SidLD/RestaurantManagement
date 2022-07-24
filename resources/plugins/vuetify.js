// src/plugins/vuetify.js

import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
// import VuetifyICon from 'vuetify/lib'

Vue.use(Vuetify, {
    iconfont: 'md', 
})

const opts = {}

export default new Vuetify(opts)