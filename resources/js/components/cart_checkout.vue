<template>
  <div>
    <navbar />

    <h2>Checkout</h2>

    <template v-if="cart">
      <div style="display: block">
        <cart :myCart="cart" :isCheckout="true"/>
        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea
                rows="4"
                v-model="notes"
                class="form-control"
                id="notes"
            />
        </div>
        <div class="text-right">
          <router-link to="/cart" class="btn btn-danger">Cancel</router-link>
          <button class="btn btn-primary" @click="checkout">Confirm Checkout</button>
        </div>
      </div>
    </template>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import cart from "./cart.vue";

export default {
    data() {
        return {
            cart: undefined,
            notes: ''
        };
    },
    methods:{
        async getCart() {
            await this.$store.dispatch("loadUserLogged");
            this.cart = this.$store.state.cart;
        },
        checkout(){
            if(typeof this.cart !== 'undefined' && this.cart.length > 0){
                axios.post('api/cart/checkout',{
                    cart : this.cart,
                    notes: this.notes,
                    customer: this.$store.state.user.id,
                    totalPrice: this.totalPrice
                }).then(result=>{
                    this.$store.commit("clearCart");
                    this.cart = this.$store.state.cart;
                    this.$socket.emit('order_checkout_request', result.data)
                    this.$toasted.show('Order created successfully!', { type: 'info'}).goAway(3500)
                    this.$router.push(`/customer/${this.$store.state.user.id}/dashboard`)
                }).catch(error=>{
                    console.log(error)
                })
            }
        }
    },
    mounted() {
        this.getCart();
    },
    computed: {
        totalPrice() {
            let sum = 0
            this.cart.forEach(product => {
                sum += product.subtotal
            });
            return sum
        }
    },
    components: { navbar, cart}
}
</script>

<style>

</style>