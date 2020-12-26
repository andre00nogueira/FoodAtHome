require('./bootstrap')

window.Vue = require('vue')

// Router
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// Websockets
import VueSocketIO from "vue-socket.io"
Vue.use(
    new VueSocketIO({
            debug: true,
            connection: "http://127.0.0.1:8080"
        })
    )

// VUEX
import store from './stores/global-store'

import AppComponent from './App.vue'
import ProductsComponent from './components/products.vue'
import CustomerComponent from './components/create_customer.vue'
import LoginComponent from'./components/login.vue'
import ShoppingCartComponent from'./components/shopping_cart.vue'
import CartCheckoutComponent from'./components/cart_checkout.vue'
import CustomerDashboardComponent from'./components/customer_dashboard.vue'
import OrderDetailsComponent from'./components/order_details.vue'
import CookDashboardComponent from'./components/cook_dashboard.vue'


Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('app', AppComponent)
Vue.component('app', CustomerComponent)

const routes = [
    { path: '/', redirect: '/index' },
    { path: '/index', component: AppComponent },
    { path: '/customers/create', component: CustomerComponent },
    { path: '/login', component: LoginComponent },
    { path: '/menu', component: ProductsComponent },
    { path: '/cart', component: ShoppingCartComponent },
    { path: '/cart/checkout', component: CartCheckoutComponent},
    { path: '/customer/:id/dashboard', component: CustomerDashboardComponent},
    { path: '/orders/:id', component: OrderDetailsComponent},
    { path: '/cook/:id/dashboard', component: CookDashboardComponent }
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
