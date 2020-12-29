<template>
  <div>
    <navbar />
    <div v-if="currentOrder">
      <h4>Current Delivery</h4>
      <h3>Current Order - #{{ currentOrder.id }}</h3>
      <br />
      <div v-if="client">
      <h3>Customer Data</h3>
      <h5>Name - {{ client.name }}</h5>
      <h5>Address - {{ client.address }}</h5>
      <h5>Phone - {{ client.phone }}</h5>
      <h5>Email - {{ client.email }}</h5>
      <h5>Photo</h5>
      </div>
      <br />
      <h4>Status - {{ currentOrder.status }}</h4>
      <h5>Delivery Started - {{ currentOrder.current_status_at }}</h5>
      <h6>Price - {{ currentOrder.total_price }}€</h6>
      <h6>Notes - {{ currentOrder.notes ? order.notes : "No notes" }}</h6>
      <div class="content">
        <h2>Items</h2>
        <itemsTable :items="currentOrder.orderItems" />
      </div>
    </div>
    <div v-else>
      <h4>My Orders</h4>
      <orderTable
        :orders="orders"
        :to-delivery="true"
        @assignOrder="deliverOrder"
      />
      <pagination
        v-if="orders.length > 0"
        :data="ordersData"
        @pagination-change-page="getResults"
      ></pagination>
    </div>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import orderTable from "./order_table.vue";
import itemsTable from "./items_table.vue";
export default {
  data() {
    return {
      orders: [],
      ordersData: {},
      client: undefined,
      currentOrder: undefined,
    };
  },
  created() {
    axios
      .get(`api/deliverymen/${this.$route.params.id}/order`)
      .then((response) => {
        if (response.data) {
          this.currentOrder = response.data.data;
          this.getClient();
        } else {
          axios.get(`api/deliverymen/orders`).then((response) => {
            this.ordersData = response.data;
            this.orders = this.ordersData.data;
          });
        }
      });
  },
  methods: {
    getResults(page = 1) {
      let url = `api/deliverymen/orders`;
      if (page != 0) {
        url += `?page=${page}`;
        axios.get(url).then((response) => {
          this.ordersData = response.data;
          this.orders = this.ordersData.data;
        });
      }
    },
    getClient() {
      axios.get(`api/customers/${this.currentOrder.customer_id}`).then((response) => {
        console.log(response.data)
        this.client = response.data.data;
      });
    },
    deliverOrder(orderID) {
      console.log("before emit");
      var order = this.orders.find((order) => {
        return order.id === orderID;
      });
      let value = "";
      if (order.status == "R") {
        value = "T";
      } else {
        value = "D";
      }
      axios
        .patch(`api/orders/${order.id}`, {
          status: value,
          delivered_by: this.$store.state.user.id,
        })
        .then((response) => {
          this.currentOrder = response.data;
          getClient()
          console.log(this.currentOrder);
          let payload = {
            userId: this.currentOrder.customer_id,
            status: this.currentOrde.status,
            orderId: this.currentOrder.id,
          };
          this.$socket.emit("order_status", payload);
          if (value === "D") {
            //todo set deliveryman available
            this.setDeliverymanAvailable();
            this.$toasted
              .show(`Order #${this.currentOrder.id} marked as delivered!`, {
                type: "success",
              })
              .goAway(3500);

            return;
          } else {
            this.$toasted
              .show(`Order #${this.currentOrder.id} marked as in transit!`, {
                type: "success",
              })
              .goAway(3500);
          }
        });
    },
    setDeliverymanAvailable() {
      axios
        .patch(`api/users/${this.$store.state.user.id}`, {
          available: new Boolean(true),
        })
        .then((response) => {
          this.currentOrder = undefined;
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  sockets: {
    order_ready_to_deliver(orderID) {
      console.log("received order ready to deliver");
      if (!this.currentOrder) {
        axios.get(`api/deliverymen/orders`).then((response) => {
          this.ordersData = response.data;
          this.orders = this.ordersData.data;
        });
      }
    },
  },
  components: { navbar, orderTable, itemsTable },
};
</script>

<style>
</style>