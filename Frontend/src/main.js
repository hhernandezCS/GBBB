import Vue from 'vue'
import {ModalPlugin, ToastPlugin} from 'bootstrap-vue'
import VueCompositionAPI from '@vue/composition-api'
import BlockUI from 'vue-blockui'
import Pagination from './@core/components/pagination/Pagination'
import axios from "axios";
import JQuery from 'jquery'
import VueQRCodeComponent from 'vue-qrcode-component'

import router from './router'
import store from './store'
import App from './App.vue'
import Vuex from 'vuex'
// Global Components
import './global-components'
// 3rd party plugins
import '@/libs/portal-vue'
import '@/libs/toastification'
import '@/libs/sweet-alerts'
import './libs/filters';
import {VueMaskDirective} from 'v-mask'


// Localhost
axios.defaults.baseURL = "http://bk.sanmartin.cs:8002/";

//red local
// axios.defaults.baseURL = "http://192.168.88.15:8001/";

// Servidor de desarrrollo
// axios.defaults.baseURL = "http://css.capital.software:8043/";
// axios.defaults.baseURL = "https://cssabk.capital.software/";

// Servidor de producción
// axios.defaults.baseURL = "https://bkpos.capital.software/";


axios.defaults.withCredentials = true;
//axios.defaults.timeout = 1000;

// BSV Plugin Registration
Vue.use(ToastPlugin);
Vue.use(ModalPlugin);
Vue.use(BlockUI);
Vue.use(Vuex);
Vue.component('qr-code', VueQRCodeComponent);

Vue.component('pagination', Pagination);

//Typeahead component
const Typeahead = () => import(/* webpackChunkName: "typeahead" */ '../src/views/Utils/Typeahead');
Vue.component('typeahead', Typeahead);

// Composition API
Vue.use(VueCompositionAPI);

// import core styles
require('@core/scss/core.scss');

// import assets styles
require('@/assets/scss/style.scss');

require('././../../Backend/resources/js/bootstrap');
require('@core/utils/exort/uploader.min');
Vue.config.productionTip = false;

window.$ = JQuery;

// import v-mask directive
Vue.directive('mask', VueMaskDirective);

// Importamos librería de validación
import {localize} from 'vee-validate'
// Set locale lang
localize('es');

import flatPickr from 'vue-flatpickr-component';
/*flatPickr({
    dateFormat: 'YYYY-MM-DD',
    locale: {
        firstDayOfWeek: 1,
        weekdays:{
            shorthand: ['DO','LU','MA','MIE','JUE','V','S'],
            longhand: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado']
        },
        months: {
            shorthand: ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'],
            longhand: ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'],
        }
    }
});*/

// import vuejs datepicker library
const Datepicker = () => import(/* webpackChunkName: "datepicker" */ 'vuejs-datepicker');
Vue.component('Datepicker', Datepicker);

new Vue({
    router,
    store,
    render: h => h(App),
}).$mount('#app');
