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

// Toasted
import Toasted from 'vue-toasted';
Vue.use(Toasted)

import AppComponent from './App.vue'
import ProductsComponent from './components/products.vue'
import CustomerComponent from './components/create_customer.vue'
import LoginComponent from'./components/login.vue'
import ShoppingCartComponent from'./components/shopping_cart.vue'
import CartCheckoutComponent from'./components/cart_checkout.vue'
import CustomerDashboardComponent from'./components/customer_dashboard.vue'
import OrderDetailsComponent from'./components/order_details.vue'
import CookDashboardComponent from'./components/cook_dashboard.vue'
import DeliverymanDashboardComponent from'./components/deliveryman_dashboard.vue'


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
    { path: '/cook/:id/dashboard', component: CookDashboardComponent },
    { path: '/deliveryman/:id/dashboard', component: DeliverymanDashboardComponent }
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
    },
    sockets: {
        order_id_message(orderID) {
            axios.patch(`api/orders/${orderID}`, {
                prepared_by: this.$store.state.user.id
            }).then((response) => {
                axios
                    .patch(`api/users/${this.$store.state.user.id}`, {
                        available: new Boolean(false),
                    })
                    .then((response) => {
                        console.log(response.data);
                        this.$toasted.show(`You've been assigned with a new order (${orderID})`, { type: 'info' }).goAway(3500)
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                
            }).catch((error) => {
                console.log(error)
            })
        },
        order_status_changed(payload) { 
            this.$toasted.show(`Order #${payload.orderId} marked as ${payload.status}!`, {type: "success",}).goAway(3500);
        },
    },
    data() {
        return {
            orderId: undefined
        }
    }
})
