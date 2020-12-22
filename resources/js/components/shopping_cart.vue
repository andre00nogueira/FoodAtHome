<template>
  <div>
    <navbar />

    <h2>My Cart</h2>

    <template v-if="cart">
      <div v-if="cart.length>0" style="display: block">
        <cart :myCart="cart" :isCheckout="false"/>
        <div v-if="checkoutAvailable" class="text-right"> 
          <a @click="clearCart" class="btn btn-danger">Clear All</a>
          <router-link to="/cart/checkout" class="btn btn-primary">Checkout</router-link>
        </div>
      </div>
      <p class="text-center" v-else >Cart is Empty</p>
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
    };
  },
  methods: {
    async getCart() {
      await this.$store.dispatch("loadUserLogged");
      this.cart = this.$store.state.cart;
      console.log(this.cart);
    },
    clearCart() {
      this.$store.commit("clearCart");
      this.cart = this.$store.state.cart;
    }
  },
  mounted() {
    this.getCart();
  },
  computed: {
    checkoutAvailable(){
      return typeof this.cart !== 'undefined' && this.cart.length > 0
    }
  },
  components: { navbar, cart },
};
</script>

<style>
  tfoot {
    display: table-footer-group;
    vertical-align: middle;
    border-color: inherit;
  }
</style>