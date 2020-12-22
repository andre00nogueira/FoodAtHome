<template>
  <div>
    <navbar />

    <h2>Checkout</h2>

    <template v-if="cart">
      <div style="display: block">
        <table id="tableProducts" class="table table-striped">
          <thead>
            <tr>
              <th />
              <th>Name</th>
              <th>Unit Price</th>
              <th>Sub Total</th>
              <th>Quantity</th>
             
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
              <td>{{ item.subtotal.toFixed(2) }}€</td>
              <td>{{ item.quantity }}</td>
            </tr>
          </tbody>
          <tr>
            <td style="text-align:right" class="font-weight-bold" colspan="12">Total: {{ totalPrice.toFixed(2) }}€</td>
          </tr>
        </table>
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
    components: { navbar }
}
</script>

<style>

</style>