const Echo = require("laravel-echo");
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
    require('admin-lte');
} catch (e) {
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

window.Pusher = require('pusher-js');

//LOCAL CONFIG
// window.Echo = new Echo.default({
//     broadcaster: 'pusher',
//     key: 'websocketkey',
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     // wssPort: 6001,
//     disableStats: false,
//     forceTLS: false,
//     enabledTransports: ['ws']
// });

window.Echo = new Echo.default({
    broadcaster: 'pusher',
    key: 'websocketkey',
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    disableStats: true,
    forceTLS: true,
    enabledTransports: ['ws', 'wss']
});