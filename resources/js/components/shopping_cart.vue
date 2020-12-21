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
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item,index) of cart" :key="item.id">
              <td>
                <img
                  id="productPhoto"
                  :src="'storage/products/' + item.photo_url"
                />
              </td>
              <td>{{ item.name }}</td>
              <td>{{ item.price }}€</td>
              <td>{{ item.subtotal.toFixed(2) }}€</td>
              <td>
                <div style="display: flex">
                  <input
                    class="form-control"
                    style="width: 15%; text-align: right"
                    :value="item.quantity"
                    min="1"
                    max="10"
                    readonly
                  />
                  <button
                    class="btn btn-secondary"
                    style="margin-left: 2%"
                    @click="removeOneUnitFromItem(item.id)"
                  >
                    ➖
                  </button>
                  <button
                    class="btn btn-primary"
                    style="margin-left: 2%"
                    @click="addOneUnitToItem(index)"
                  >
                    ➕
                  </button>
                  <button
                    class="btn btn-danger"
                    style="margin-left: 2%"
                    @click="clearCartItem(index)"
                  >
                    🗑️
                  </button>
                </div>
              </td>
              <td></td>
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
    clearCartItem(index){
      this.cart.splice(index, 1);
      this.$store.commit('removeItemFromCart', index);
    },
    addOneUnitToItem(index){
      let item = this.cart[index]
      item.quantity++
      item.subtotal = item.quantity * item.price
      this.$store.commit('addOneUnitToItem', index)
    }
  },
  mounted() {
    this.getCart();
  },
  components: { navbar },
};
</script>

<style>
</style>