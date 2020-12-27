<template>
  <div>
    <navbar />

    <h2>Cook Dashboard</h2>
    <div class="content" v-if="noOrder">
      <h3>Waiting for a new order to arrive!</h3>
    </div>
    <div class="content" v-if="!isFetching && !noOrder">
      <div class="buttons">
        <button
          v-if="orderStatus === 'Holding'"
          class="btn btn-primary"
          @click="markOrder('P')"
        >
          Mark Order as Preparing
        </button>
        <button
          v-if="orderStatus === 'Preparing'"
          class="btn btn-primary"
          @click="markOrder('R')"
        >
          Mark Order as Ready
        </button>
      </div>
      <br />
      <h3>Current Order - #{{ order.id }}</h3>
      <br />
      <h3>Customer - {{ order.customer_name }}</h3>
      <h4>Status - {{ orderStatus }}</h4>
      <h5>Preparation Started - {{ order.opened_at }}</h5>
      <h6>Price - {{ order.total_price }}€</h6>
      <h6 v-if="order.notes">Notes - {{ order.notes }}</h6>
      <h6 v-else>Notes - No notes</h6>

      <div class="content">
        <h2>Items</h2>
        <itemsTable :items="order.orderItems" />
      </div>
    </div>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import itemsTable from "./items_table.vue";

export default {
  data() {
    return {
      order: undefined,
      orderItems: [],
      orderStatus: undefined,
      isFetching: true,
      noOrder: false,
    };
  },
  methods: {
    getCurrentOrder(orderId) {
      console.log(orderId);
      if (orderId === "") {
        // TODO make available_at equal to current date
        //this.setCookAvailable(true);
        this.noOrder = true;
        return;
      }
      this.setCookAvailable(false);
      this.noOrder = false;
      axios
        .get(`api/orders/${orderId}`)
        .then((response) => {
          this.order = response.data.data;
          switch (this.order.status) {
            case "H":
              this.orderStatus = "Holding";
              break;
            case "P":
              this.orderStatus = "Preparing";
              break;
          }
          this.isFetching = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getCurrentOrderId() {
      axios
        .get(`api/cook/${this.$store.state.user.id}/currentOrder`)
        .then((response) => {
          this.getCurrentOrder(response.data);
        });
    },
    markOrder(value) {
      // 1 - Preparing
      // 2 - Ready
      if (value != "P" && value != "R") {
        return;
      }
      axios.patch(`api/orders/${this.order.id}`, {
          status: value,
        })
        .then((response) => {
          if (value === "R") {
            this.setCookAvailable(true);
            this.noOrder = true;
            this.$toasted
              .show(`Order #${this.order.id} marked as ready!`, {
                type: "success",
              })
              .goAway(3500);

            return;
          } else {
            this.orderStatus = "Preparing";
            this.$toasted
              .show(`Order #${this.order.id} marked as preparing!`, {
                type: "success",
              })
              .goAway(3500);
          }
        });
    },
    setCookAvailable(value) {
      axios
        .patch(`api/users/${this.$store.state.user.id}`, {
          available: new Boolean(value),
        })
        .then((response) => {
          if (value === true) {
            console.log(value)
            this.$socket.emit("order_ready", this.$store.state.user.id);
          }
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  sockets: {
    order_id_message(orderID) {
      this.getCurrentOrder(orderID);
    },
  },
  mounted() {
    this.getCurrentOrderId();
  },
  components: { navbar, itemsTable },
};
</script><style>
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