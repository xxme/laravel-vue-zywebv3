
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
        // TOPページ
        { path: '/admin', component: EventsIndex },
        { path: '/admin/event/create', component: EventForm },
        { path: '/admin/event/create/:eventdate', component: EventForm },
        { path: '/admin/event/:eventid/edit/', component: EventForm },
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
        }
    }
});
