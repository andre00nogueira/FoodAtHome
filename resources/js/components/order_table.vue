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
            <tr v-for="order of orders" :key="order.id">
              <td>{{getStatus(order.status)}}</td>
              <td>{{ order.total_price }}€</td>
              <td>{{order.date}}</td>
              <td><router-link :to="`/orders/${order.id}`">Details</router-link></td>
            </tr>
          </tbody>
        </table>
        <p class="text-center" v-else >There are no orders</p>
  </div>
</template>

<script>
export default {
    props:['orders'],
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
        }
  }
}
</script>

<style>

</style>