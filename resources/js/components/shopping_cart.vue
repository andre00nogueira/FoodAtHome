<template>
  <div>
    <navbar />

    <h2>My Cart</h2>

    <template v-if="cart">
      <div style="display: block">
        <a @click="clearCart" class="btn btn-primary">Clear All</a>
        <table id="tableProducts" class="table table-striped">
          <thead>
            <tr>
              <th />
              <th>Name</th>
              <th>Unit Price</th>
              <th>Sub Total</th>
              <th>Quantity</th>
              <th>Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item of cart" :key="item.id">
              <td>
                <img
                  id="productPhoto"
                  :src="'storage/products/' + item.photo_url"
                />
              </td>
              <td>{{ item.name }}</td>
              <td>{{ item.price }}€</td>
              <td>{{ item.subtotal }}€</td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.total }}€</td>
              <td>
                <div style="display: flex">
                  <input
                    type="number"
                    class="form-control"
                    style="width: 65%"
                    placeholder="Quantity"
                    min="1"
                    max="10"
                  />
                  <button
                    class="btn btn-primary"
                    style="margin-left: 2%"
                  >
                    🛒
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </template>
  </div>
</template>

<script>
import navbar from "./navbar.vue";

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
    },
  },
  mounted() {
    this.getCart();
  },
  components: { navbar },
};
</script>

<style>
</style>