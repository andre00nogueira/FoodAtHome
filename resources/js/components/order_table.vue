<template>
    <div>
      <table v-if="orders.length>0"  id="tableProducts" class="table table-striped">
          <thead>
            <tr>
              <th>Status</th>
              <th>Total Price</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(order,index) of orders" :key="order.id">
              <td>{{getStatus(order.status)}}</td>
              <td>{{ order.total_price }}€</td>
              <td>{{order.date}}</td>
              <td v-if="!toDelivery"><router-link :to="`/orders/${order.id}`">Details</router-link></td>
              <td v-else><button v-if="index==0 && page==1" class="btn btn-link" @click="deliverOrder(order.id)">Deliver Order</button></td>
            </tr>
          </tbody>
        </table>
        <p class="text-center" v-else >There are no orders</p>
  </div>
</template>

<script>
export default {
    props:['orders','toDelivery','page'],
    methods:{
        getStatus(status){
            switch(status){
                case 'H': 
                    return "Holding";
                case 'P':
                    return "Preparing";
                case 'R':
                    return "Ready";
                case 'T':
                    return "In Transit";
                case 'D':
                    return "Delivered";
                default:
                    return "Cancelled";
            }
        },
        deliverOrder(orderID){
          console.log("until emit")
          this.$emit('assignOrder',orderID)
        }
  }
}
</script>

<style>

</style>