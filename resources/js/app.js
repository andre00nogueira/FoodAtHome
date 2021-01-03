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
//import CustomerComponent from './components/create_customer.vue'
import CreateUserComponent from './components/create_user.vue'
import LoginComponent from './components/login.vue'
import ShoppingCartComponent from './components/shopping_cart.vue'
import CartCheckoutComponent from './components/cart_checkout.vue'
import CustomerDashboardComponent from './components/customer_dashboard.vue'
import OrderDetailsComponent from './components/order_details.vue'
import CookDashboardComponent from './components/cook_dashboard.vue'
import CreateProductComponent from './components/create_product.vue'
import EditProductComponent from './components/edit_product.vue'
import UsersComponent from './components/users.vue'
import DeliverymanDashboardComponent from './components/deliveryman_dashboard.vue'
import EditUserComponent from './components/edit_user.vue'
import EditCustomerComponent from './components/edit_customer.vue'
import ProfileComponent from './components/profile.vue'
import ChangeUserPasswordComponent from './components/change_user_password.vue'


Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('app', AppComponent)
//Vue.component('app', CustomerComponent)

/*function acessCustomerDashboard(to, from, next){
    if(store.state.user.id==to.params.id){
        console.log("1")
        next()
    }else {
        next(`/customer/${store.state.user.id}/dashboard`)
        
    }
    
}*/

const routes = [
    { path: '/', redirect: '/index' },
    { path: '/index', component: AppComponent },
    { path: '/users/create', name: 'createUser', component: CreateUserComponent },
    { path: '/customers/create', name: 'createCustomer', component: CreateUserComponent },
    { path: '/users/:id/edit', component: EditUserComponent },
    { path: '/users/:id', component: ProfileComponent },
    { path: '/login', component: LoginComponent },
    { path: '/menu', component: ProductsComponent },
    { path: '/cart', component: ShoppingCartComponent},
    { path: '/cart/checkout', component: CartCheckoutComponent},
    { path: '/customer/:id/dashboard', component: CustomerDashboardComponent/*, beforeEnter: acessCustomerDashboard*/},
    { path: '/orders/:id', component: OrderDetailsComponent },
    { path: '/cook/:id/dashboard', component: CookDashboardComponent },
    { path: '/products/create', component: CreateProductComponent},
    { path: '/products/:id/edit', component: EditProductComponent},
    { path: '/users', component: UsersComponent },
    { path: '/deliveryman/:id/dashboard', component: DeliverymanDashboardComponent },
    { path: '/users/:id/password', component: ChangeUserPasswordComponent }
]

const router = new VueRouter({
    routes: routes
})



const app = new Vue({
    el: '#app',
    router,
    store,
    async mounted() {
        await store.dispatch('loadUserLogged')
        router.beforeEach((to, from, next) => {
            if (to.path !== '/login' && to.path !== '/index' && to.path !== '/menu' && to.name !== 'createCustomer' && !store.state.user) next('/login')
            if (to.path == '/users' && store.state.user && store.state.user.type != 'EM') next('/')
            else next()
        })
    },
    sockets: {
        blocked(userID) {
            if (userID) {
                axios
                    .post("/api/logout")
                    .then((response) => {
                        console.log("User has logged out");
                        // This updates the store
                        // And sets current user to NULL
                        this.$store.commit("clearCart");
                        axios
                            .patch(`/api/users/${this.$store.state.user.id}`, {
                                loggedin: new Boolean(false),
                                available: new Boolean(false),
                            })
                            .then((response) => {
                                console.log(response.data);
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                        this.$store.commit("clearUser");
                        this.$router.push("/login");
                    })
                    .catch((error) => {
                        console.log(`Invalid Logout ${error}`);
                    });
                this.$toasted.show(`You've been blocked by a manager!`, { type: 'error' }).goAway(3500)
            }
        },
        new_order(orderID) {
            if (orderID) {
                this.$toasted.show(`You've been assigned with a new order (${orderID})`, { type: 'info' }).goAway(3500)
            }
        },
        order_status_changed(payload) {
            let status = ""
            switch (payload.status) {
                case 'P':
                    status = "Preparing"
                    break
                case 'R':
                    status = "Ready"
                    break
            }
            this.$toasted.show(`Order #${payload.orderId} marked as ${status}!`, { type: "success", }).goAway(3500);
        },
    },
    data() {
        return {
            orderId: undefined
        }
    }
})
