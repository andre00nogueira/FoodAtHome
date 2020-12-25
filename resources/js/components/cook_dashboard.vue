<template>
  <div>
    <navbar />

    <h2>Cook Dashboard</h2>
    <div class="content" v-if="!isFetching">
      <h3>Current Order - #{{ order.id }}</h3>
      <br />
      <h3>Customer - {{ order.customer_name }}</h3>
      <h4>Status - {{ order.status }}</h4>
      <h5>Preparation Started - {{ order.opened_at }}</h5>
      <h6>Price - {{ order.total_price }}€</h6>
      <h6 v-if="order.notes">Notes - {{ order.notes }}</h6>
      <h6 v-else>Notes - No notes</h6>

      <div class="content">
        <h2>Items</h2>
        <table id="tableItems" class="table table-striped">
          <thead>
            <tr>
              <th />
              <th>Name</th>
              <th>Quantity</th>
              <th>Sub Total</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item of order.orderItems" :key="item.id">
              <td>
                <img
                  id="itemPhoto"
                  :src="'storage/products/' + item.photo_url"
                />
              </td>
              <td>{{ item.name }}</td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.sub_total_price }}€</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import navbar from "./navbar.vue";

export default {
  data() {
    return {
      order: undefined,
      orderItems: [],
      isFetching: true,
    };
  },
  methods: {
    getCurrentOrder(orderId) {
      axios
        .get(`api/orders/${orderId}`)
        .then((response) => {
          this.order = response.data.data;
          console.log(response.data.data);
          switch (this.order.status) {
            case "H":
              this.order.status = "Holding";
              break;
            case "P":
                this.order.status = "Preparing";
                break;
            case "R":
                this.order.status = "Ready";
                break;
            case "T":
                this.order.status = "In Transit";
                break;
            case "D":
                this.order.status = "Delivered";
                break;
            default:
                this.order.status = "Cancelled";
                break;
          }
          this.isFetching = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  mounted() {
    this.getCurrentOrder(3);
  },
  components: { navbar },
};
</script>

<style>
.content {
  text-align: center;
  margin-top: 3%;
}

#tableItems {
  margin-top: 2%;
  margin-bottom: 3%;
}

#itemPhoto {
  width: 50px;
  height: 50px;
}
</style>