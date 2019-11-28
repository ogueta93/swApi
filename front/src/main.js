import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import axios from 'axios';
import VueAxios from 'vue-axios';

import App from '@/App';
import Router from '@/router';

Vue.config.productionTip = false

Vue.use(BootstrapVue);
Vue.use(VueAxios, axios);
Vue.config.productionTip = false;

const vue = new Vue({
    el: "#app",
    router: Router,
    render: h => h(App),

    data() {
        return {
        }
    },
    mounted: function() {
    }
});

window.app = vue;