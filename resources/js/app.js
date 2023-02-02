import Vue from "vue";
import Index from "./components/Index"
import router from "./router";
import Notifications from 'vue-notification'

require('./bootstrap');

new Vue({
    el: "#app",

    components: {
        Index
    },

    router
})

Vue.use(Notifications)
