/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

require('../../bower_components/select2/dist/js/select2.min.js')


import moment from 'moment';

import { AlertError, Form, HasError } from 'vform';
import Gate from "./Gate";
import Swal from 'sweetalert2';
import VueProgressBar from 'vue-progressbar'
/**
 * Routes imports and assigning
 */
import VueRouter from 'vue-router';
import routes from './routes';

window.Form = Form;

Vue.prototype.$gate = new Gate(window.user);


const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
window.Swal = Swal;
window.Toast = Toast;

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
});

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)


Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes
});
// Routes End


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


// Components
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('dashboard', require('./components/Dashboard.vue'));

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue').default
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue').default
);

Vue.component(
    'not-found',
    require('./components/NotFound.vue').default
);


import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/pt-br'

Vue.use(ElementUI, { locale })

// Filter Section

Vue.filter('myDate', function (created) {
    return moment(created).format('MMMM Do YYYY');
});

Vue.filter('gender', function (value) {
    return (value === 'F') ? "Feminino" : "Masculino";
});

Vue.filter('yesno', value => (value ? '<i class="fas fa-check green"></i>' : '<i class="fas fa-times red"></i>'));

// end Filter

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app',
    router
});

//Mascaras
require('jquery-mask-plugin')
$('.date').mask("99/99/9999", { placeholder: 'MM/DD/YYYY' });
$('.positiveNumber').mask('Z#', {
    translation: {
        'Z': {
            pattern: /[0-9]/
        }
    }
});
$('.money').mask("#################.##", { reverse: true });
$('.stockCode').mask('SSSS00', {
    'translation': {
        S: { pattern: /[A-Za-z]/ },
        0: { pattern: /[0-9]/ }
    }
    , onKeyPress: function (value, event) {
        event.currentTarget.value = value.toUpperCase();
    }
});
