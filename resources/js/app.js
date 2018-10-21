
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('phone-form-component', require('./components/PhoneFormComponent.vue'));

//axios.defaults.headers.common['Authorization'] = Laravel.csrfToken;
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

const app = new Vue({
    el: '#app',
    methods: {
        submitLogoutForm: function() {
            document.getElementById('logout-form').submit();
        },
        deletePhone: async function(url, event) {
            try {
                let data = await axios.delete(url);
                // Remove the line from the DOM
                $('#phone-' + data.data.data.id).remove();
            } catch (error) {
                alert(error);
            }
        },
        addPhoneForm: function() {
            $('<div id="new-phone"></div>').insertBefore('#add-phone-form');
            new Vue({
                el: '#new-phone',
                component: 'phone-form-component',
                template: '<phone-form-component/>'
            })
        }
    }
});
