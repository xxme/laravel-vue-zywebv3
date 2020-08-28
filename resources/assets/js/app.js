
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
import AWS from 'aws-sdk';
// import S3 from 'aws-sdk/clients/s3';
import EventsIndex from './components/Events/Index.vue';
import EventForm from './components/Events/EventForm.vue';
import EventContract from './components/Events/EventContract.vue';
import ProductListsIndex from './components/ProductLists/Index.vue';
import ProductListForm from './components/ProductLists/ListForm.vue';
import EstimateIndex from './components/Estimates/Index.vue';
import Finances from './components/Events/Money.vue';
import Offer from './components/Public/Offer.vue';

AWS.config.accessKeyId = "AKIAQPXYC2SAWTKM7OYE";
AWS.config.secretAccessKey = "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";
AWS.config.update({region: 'ap-northeast-1'});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.prototype.$http = axios;
Vue.prototype.FatalError = function (response){
    this.$http.post('/admin/savelog', response).then().catch(error => {
        console.log(error.response.data.message);
    });
}
Vue.prototype.getS3Url = function (keylist) {
    const s3 = new AWS.S3();
    const bucket = 's3-koyoshieki';
    var imgs = [];

    for (var key in keylist) {
        if (keylist[key].indexOf('AWSAccessKeyId') > -1) {
            imgs.push(keylist[key]);
        } else {
            var s3key = 'uploads/' + keylist[key].replace("_thumb", "");
            var params = {Bucket: bucket, Key: s3key, Expires: 60 * 30};  // Expires:有効期限(秒)
            var url = s3.getSignedUrl('getObject', params);
            // console.log(url);
            imgs.push(url);
            console.log("s3----get--------")
        }
    }
    return imgs;
}
Vue.use(VueRouter);
Vue.use(VueI18n);
// Vue.use(AWS);
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
        { path: '/admin/event/contract/:eventid', component: EventContract },
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
