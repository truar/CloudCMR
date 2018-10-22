
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
Vue.component('address-form-component', require('./components/AddressFormComponent.vue'));
Vue.component('member-table-component', require('./components/MemberTableComponent.vue'));

//axios.defaults.headers.common['Authorization'] = Laravel.csrfToken;
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

const app = new Vue({
    el: '#app',
    data: {
        addressCount: $('.address-form').length,
        phoneCount: $('.phone-form').length,
    },
    methods: {
        submitLogoutForm: function() {
            document.getElementById('logout-form').submit();
        },
        deletePhone: async function(url, event) {
            try {
                let data = await axios.delete(url);
                // Remove the line from the DOM
                $('#phone-' + data.data.data.id).remove();
                this.phoneCount--;
            } catch (error) {
                alert(error);
            }
        },
        addPhoneForm: function() {
            this.phoneCount++;
            $('<div id="new-phone"></div>').insertBefore('#add-phone-form');
            let vm = this;
            new Vue({
                el: '#new-phone',
                component: 'phone-form-component',
                methods: {
                    deletePhoneForm: function(phoneId) {
                        $('#' + phoneId).remove();
                        vm.phoneCount--;
                    }
                },
                template: '<phone-form-component v-on:delete-phone-form=\'deletePhoneForm\' phone-key="' + this.phoneCount + '"/>'
            });
        },
        deleteAddress: async function(url, event) {
            try {
                let data = await axios.delete(url);
                // Remove the line from the DOM
                $('#address-' + data.data.data.id).remove();
                this.addressCount--;
            } catch (error) {
                alert(error);
            }
        },
        addAddressForm: function() {
            this.addressCount++;
            $('<div id="new-address"></div>').insertBefore('#add-address-form');
            let vm = this;
            new Vue({
                el: '#new-address',
                component: 'address-form-component',
                methods: {
                    deleteAddressForm: function(addressId) {
                        $('#' + addressId).remove();
                        vm.addressCount--;
                    },
                },
                template: '<address-form-component v-on:delete-address-form="deleteAddressForm" address-key="' + this.addressCount + '"/>'
            })
        },
        searchMembers: async function(url, event) {
            let searchText = {searchText: $('#searchText').val()};
            let vm = this;
            try {
                let data = await axios.post(url, searchText);
                vm.members = data.data.data;
                new Vue({
                    el: '#members-table',
                    data: {
                        members: vm.members
                    },
                    component: 'member-table-component',
                    template: '<member-table-component v-bind:members="members"></member-table-component>'
                })
            } catch(error) {
                console.log(error);
            }
        }
    }
});
