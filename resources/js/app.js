require('./bootstrap')

window.Vue = require('vue')

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import AppComponent from './App.vue'
import ProductsComponent from './components/products.vue'

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('app', AppComponent)

const routes = [
    { path: '/', redirect: '/index' },
    { path: '/index', component: AppComponent },
    { path: '/menu', component: ProductsComponent }
]

const router = new VueRouter({
    routes: routes
})

const app = new Vue({
    el: '#app',
    router
})
