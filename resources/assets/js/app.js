
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');
import Vue from 'vue';
import VueRouter from 'vue-router';
import VueI18n from 'vue-i18n';
import EventsIndex from './components/Events/Index.vue';
import EventForm from './components/Events/EventForm.vue';
import ProductListsIndex from './components/ProductLists/Index.vue';
import ProductListForm from './components/ProductLists/ListForm.vue';
import EstimateIndex from './components/Estimates/Index.vue';
import Finances from './components/Events/Money.vue';
import Offer from './components/Public/Offer.vue';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.prototype.$http = axios;
Vue.use(VueRouter);
Vue.use(VueI18n);
// Vue.component('example-component', require('./components/ExampleComponent.vue'));

const router = new VueRouter({
    mode: 'history',
    routes: [
        // For admin
        // TOPページ
        { path: '/admin', component: EventsIndex },
        { path: '/admin/event/create', component: EventForm },
        { path: '/admin/event/create/:eventdate', component: EventForm },
        { path: '/admin/event/createbyproductlist/:listid', component: EventForm },
        { path: '/admin/event/createbyorder/:orderid', component: EventForm },
        { path: '/admin/event/:eventid/edit/', component: EventForm },
        { path: '/admin/productlist', component: ProductListsIndex },
        { path: '/admin/productlist/create', component: ProductListForm },
        { path: '/admin/productlist/:listid/edit/', component: ProductListForm },
        { path: '/admin/estimate', component: EstimateIndex },
        { path: '/admin/finances', component: Finances },

        // For customer
        { path: '/offer', component: Offer },
        { path: '/offer/:partner', component: Offer },
        { path: '/offerzip/:zipcode', component: Offer },
    ]
});

const i18ndata = require('./i18n.json');
const i18n = new VueI18n({
    locale: 'cn', // デフォルト言語はjaにしておくが、ブラウザの言語を拾ってきてここに入れる => 言語変更されたら書き換える
    messages: i18ndata
});

const app = new Vue({
    router,
    el: '#app',
    i18n: i18n,
    methods: {
        handleClick_changeLanguage(lang) {
            this.$i18n.locale = lang;
        },
        formatNumberJPY(number) {
            if(number) {
                return new Intl.NumberFormat('ja-JP', { style: 'currency', currency: 'JPY' }).format(number);
            }
        }
    }
});
