require('./bootstrap')

window.Vue = require('vue')

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import AppComponent from './App.vue'
import ProductsComponent from './components/products.vue'
import CustomerComponent from './components/customer/create_customer.vue'
import LoginComponent from'./components/login'

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('app', AppComponent)
Vue.component('app', CustomerComponent)

const routes = [
    { path: '/', redirect: '/index' },
    { path: '/index', component: AppComponent },
    { path: '/products', component: ProductsComponent },
    { path: '/customers/create', component: CustomerComponent },
    { path: '/login', component: LoginComponent },
    { path: '/menu', component: ProductsComponent }
]

const router = new VueRouter({
    routes: routes
})

const app = new Vue({
    el: '#app',
    router
})
