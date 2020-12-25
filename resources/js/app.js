require('./bootstrap')

window.Vue = require('vue')


import VueRouter from 'vue-router'
Vue.use(VueRouter)

// VUEX
import store from './stores/global-store'

import AppComponent from './App.vue'
import ProductsComponent from './components/products.vue'
import CustomerComponent from './components/customer/create_customer.vue'
import EditUserComponent from './components/user/edit_user.vue'
import EditCustomerComponent from './components/customer/edit_customer.vue'
import ProfileComponent from './components/profile.vue'
//import CustomerComponent from './components/create_customer.vue'
import LoginComponent from'./components/login.vue'
import ShoppingCartComponent from'./components/shopping_cart.vue'
import CartCheckoutComponent from'./components/cart_checkout.vue'


Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('app', AppComponent)
Vue.component('app', CustomerComponent)

const routes = [
    { path: '/', redirect: '/index' },
    { path: '/index', component: AppComponent },
    { path: '/customers/create', component: CustomerComponent },
    { path: '/users/edit/:id', component: EditUserComponent },
    { path: '/customers/edit/:id', component: EditCustomerComponent },
    { path: '/users/:id', component: ProfileComponent },
    { path: '/customers/create', component: CustomerComponent },
    { path: '/login', component: LoginComponent },
    { path: '/menu', component: ProductsComponent },
    { path: '/cart', component: ShoppingCartComponent },
    { path: '/cart/checkout', component: CartCheckoutComponent}
]

const router = new VueRouter({
    routes: routes
})

const app = new Vue({
    el: '#app',
    router,
    store,
    mounted() {
        store.dispatch('loadUserLogged')
    }
})
