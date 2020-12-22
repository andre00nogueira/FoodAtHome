require('./bootstrap')

window.Vue = require('vue')

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import AppComponent from './App.vue'
import ProductsComponent from './components/products.vue'
import CustomerComponent from './components/customer/create_customer.vue'
import EditUserComponent from './components/user/edit_user.vue'
import EditCustomerComponent from './components/customer/edit_customer.vue'


Vue.component('app', AppComponent)
Vue.component('app', CustomerComponent)

const routes = [
    { path: '/', redirect: '/index' },
    { path: '/index', component: AppComponent },
    { path: '/products', component: ProductsComponent },
    { path: '/customers/create', component: CustomerComponent },
    { path: '/users/edit/:id', component: EditUserComponent },
    { path: '/customers/edit/:id', component: EditCustomerComponent }
]

const router = new VueRouter({
    routes: routes
})

const app = new Vue({
    el: '#app',
    router
})
