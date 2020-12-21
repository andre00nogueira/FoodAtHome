import Vue from "vue"
import Vuex from "vuex"

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: null,
        cart: [],
    },
    mutations: {
        clearUser(state) {
            state.user = null
        },
        setUser(state, user) {
            state.user = user
            state.cart = JSON.parse(localStorage.getItem('cart' + state.user.id))
        },
        clearCart(state) {
            state.cart = []
            localStorage.setItem('cart' + state.user.id, JSON.stringify(state.cart))
        },
        setCart(state, cart) {
            state.cart = cart
            localStorage.setItem('cart' + state.user.id, JSON.stringify(state.cart))
        },
        removeItemFromCart(state, itemId){
            state.cart.splice(itemId, 1)
            localStorage.setItem('cart' + state.user.id, JSON.stringify(state.cart))
        },
        addOneUnitToItem(state, itemId){
            let item = state.cart[itemId]
            state.cart[itemId].quantity = item.quantity++
            state.cart[itemId].subtotal = item.quantity * item.price

            localStorage.setItem('cart' + state.user.id, JSON.stringify(state.cart))
        },
        removeOneUnitToItem(state, itemId){
            let item = state.cart[itemId]
            state.cart[itemId].quantity = item.quantity--
            state.cart[itemId].subtotal = item.quantity * item.price

            localStorage.setItem('cart' + state.user.id, JSON.stringify(state.cart))
        },
        addItemToCart(state, itemCart) {
            state.cart = JSON.parse(localStorage.getItem('cart' + state.user.id)) || []
            console.log(state.cart)
            console.log(typeof(state.cart))
            var includes = false
            var  index
            state.cart.forEach((element, i) => {
                if (element.id == itemCart.id) {
                    includes = true
                    index = i
                    return
                }
            })
            if (!("quantity" in itemCart)) {
                itemCart.quantity = 1
            }
            let product
            if (!includes) {
                state.cart.push(itemCart)
                product = state.cart[state.cart.length -1]
            } else {
                product = state.cart[index]
                let quantity = Number(product.quantity)
                quantity += Number(itemCart.quantity)
                product.quantity = quantity
            }

            let quantity = Number(product.quantity)
            let price = Number(product.price)
            product.subtotal = price * quantity

            localStorage.setItem('cart' + state.user.id, JSON.stringify(state.cart))
        }
    },
    actions: {
        loadUserLogged(context) {
            return axios.get('/api/users/me').then(response => {
                context.commit('setUser', response.data)
            }).catch(error => {
                context.commit('clearUser')
            })
        },
    }
})