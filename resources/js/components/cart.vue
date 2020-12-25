<template>
  <div>
      <table  id="tableProducts" class="table table-striped">
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
            <tr v-for="(item,index) of myCart" :key="item.id">
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
                  <div v-if="!isCheckout" style="display:flex">
                    <button
                        class="btn btn-secondary"
                        style="margin-left: 2%"
                        @click="removeOneUnitFromItem(index)"
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
                </div>
              </td>
            </tr>
          </tbody>
          <tr>
            <td style="text-align:right" colspan="12">Total: {{ totalPrice.toFixed(2) }}€</td>
          </tr>
        </table>
  </div>
</template>

<script>
export default {
    props:['myCart','isCheckout'],
    methods:{
        clearCartItem(index){
            this.$store.commit('removeItemFromCart', index);
        },
        addOneUnitToItem(index){
            let item = this.myCart[index]
            item.quantity++
            item.subtotal = item.quantity * item.price
            this.$store.commit('addOneUnitToItem', index)
        },
        removeOneUnitFromItem(index){
            let item = this.myCart[index]
            if (item.quantity == 1){
                return
            }
            item.quantity--
            item.subtotal = item.quantity * item.price
            this.$store.commit('removeOneUnitToItem', index)
        }
    },
    computed:{
        totalPrice() {
            let sum = 0
            this.myCart.forEach(product => {
                sum += product.subtotal
            });
            return sum
        }
    }
}
</script>

<style>

</style>