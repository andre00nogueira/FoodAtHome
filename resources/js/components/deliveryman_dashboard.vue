<template>
  <div>
    <navbar />
    <div v-if="currentOrder">
      <h4>Current Delivery</h4>
      <div class="content" v-if="currentOrder">
        <div class="buttons">
          <button class="btn btn-primary" @click="completeOrder">
            Mark Order as Delivered
          </button>
        </div>
        <h3>Current Order - #{{ currentOrder.id }}</h3>
        <br />
        <div v-if="client">
          <h3>Customer Data</h3>
          <h5>Name - {{ client.name }}</h5>
          <h5>Address - {{ client.address }}</h5>
          <h5>Phone - {{ client.phone }}</h5>
          <h5>Email - {{ client.email }}</h5>
          <img
            class="img-profile rounded-circle"
            style="width: 100px; height: 100px"
            :src="`storage/fotos/${client.photo_url || 'default_avatar.jpg'}`"
          />
        </div>
        <br />
        <h4>Status - {{ currentOrder.status }}</h4>
        <h5>Delivery Started - {{ currentOrder.current_status_at }}</h5>
        <chronometer :initial-time="currentOrder.current_status_at" />
        <h6>Price - {{ currentOrder.total_price }}€</h6>
        <h6>Notes - {{ currentOrder.notes ? currentOrder.notes : "No notes" }}</h6>
        <div class="content">
          <h2>Items</h2>
          <itemsTable
            v-if="currentOrder.orderItems"
            :items="currentOrder.orderItems"
          />
        </div>
      </div>
    </div>
    <div v-else>
      <h4>My Orders</h4>
      <orderTable
        :orders="orders"
        :to-delivery="true"
        :page="page"
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
import chronometer from "./chronometer.vue";
export default {
  data() {
    return {
      orders: [],
      ordersData: {},
      client: undefined,
      currentOrder: undefined,
      page: 1,
    };
  },
  created() {
    axios
      .get(`api/employee/${this.$route.params.id}/currentOrder`)
      .then((response) => {
        console.log(response);
        if (response.data) {
          this.currentOrder = response.data.data;
          console.log(this.currentOrder);
          this.getClient();
        } else {
          this.getOrdersToDeliver();
        }
      });
  },
  methods: {
    getResults(page = 1) {
      let url = `api/deliverymen/orders`;
      if (page != 0) {
        this.page = page;
        url += `?page=${page}`;
        axios.get(url).then((response) => {
          this.ordersData = response.data;
          this.orders = this.ordersData.data;
        });
      }
    },
    getClient() {
      axios
        .get(`api/customers/${this.currentOrder.customer_id}`)
        .then((response) => {
          console.log(response.data);
          this.client = response.data.data;
        });
    },
    getOrdersToDeliver() {
      axios.get(`api/deliverymen/orders`).then((response) => {
        this.ordersData = response.data;
        this.orders = this.ordersData.data;
      });
    },
    deliverOrder(orderID) {
      console.log("deliver order");
      axios
        .patch(`api/orders/${orderID}`, {
          status: "T",
          delivered_by: this.$store.state.user.id,
        })
        .then((response) => {
          axios
            .patch(`api/users/${this.$store.state.user.id}`, {
              available: new Boolean(false),
            })
            .then((response) => {
              console.log("testing");
              console.log(response);
            });
          axios
            .get(`api/employee/${this.$route.params.id}/currentOrder`)
            .then((response) => {
              this.currentOrder = response.data.data;
              this.getClient();
              let payload = {
                userId: this.currentOrder.customer_id,
                status: this.currentOrder.status,
                orderId: this.currentOrder.id,
              };
              this.$socket.emit("order_status", payload);
              this.$toasted
                .show(
                  `Order #${this.currentOrder.id} assigned to me and marked as in transit!`,
                  {
                    type: "success",
                  }
                )
                .goAway(3500);
            });
        });
    },
    setDeliverymanAvailable() {
      axios
        .patch(`api/users/${this.$store.state.user.id}`, {
          available: new Boolean(true),
        })
        .then((response) => {
          this.currentOrder = undefined;
          this.getOrdersToDeliver();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    completeOrder() {
      console.log("complete order");
      axios
        .patch(`api/orders/${this.currentOrder.id}`, {
          status: "D",
        })
        .then((response) => {
          console.log(response);
          this.currentOrder = response.data.data;
          let payload = {
            userId: this.currentOrder.customer_id,
            status: this.currentOrder.status,
            orderId: this.currentOrder.id,
          };
          this.setDeliverymanAvailable();
          this.$toasted
            .show(`Order #${this.currentOrder.id} marked as delivered!`, {
              type: "success",
            })
            .goAway(3500);
          this.$socket.emit("order_status", payload);
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
    order_cancelled(orderID) {
      if (this.currenOrder && orderID == this.currenOrder.id) {
        axios
        .patch(`api/users/${this.$store.state.user.id}`, {
          available: new Boolean(true),
        })
        .then((response) => {
          this.currentOrder = undefined;
          this.getOrdersToDeliver();
        })
        .catch((error) => {
          console.log(error);
        });
        this.$toasted
          .show(
            `Your current order has been cancelled by a Manager (${orderID})`,
            { type: "danger" }
          )
          .goAway(3500);
      }
      if (this.orders.findIndex((order) => (order.id = orderID)) != -1) {
        this.getResults(this.page);
      }
    },
  },
  beforeRouteUpdate(to, from, next){
    if(to.path == `/deliveryman/${to.params.id}/dashboard` && to.params.id != this.$store.state.user.id){
      return next(`/deliveryman/${this.$store.state.user.id}/dashboard`)
    }
    next()
  },
  components: { navbar, orderTable, itemsTable, chronometer },
};
</script>

<style>
</style>