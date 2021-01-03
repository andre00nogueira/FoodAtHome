<template>
  <div>
    <navbar />

    <h2>Cook Dashboard</h2>

    <div class="content" v-if="order">
      <div class="buttons">
        <button
          v-if="order.status === 'H'"
          class="btn btn-primary"
          @click="markOrder('P')"
        >
          Mark Order as Preparing
        </button>
        <button
          v-if="order.status === 'P'"
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
      <h4>Status - {{ order.status == "H" ? "Holding" : "Preparing" }}</h4>
      <h5>Preparation Started - {{ order.opened_at }}</h5>
      <chronometer
        v-if="order.status == 'P'"
        :initial-time="order.current_status_at"
      />
      <h6>Price - {{ order.total_price }}€</h6>
      <h6>Notes - {{ order.notes ? order.notes : "No notes" }}</h6>
      <div class="content">
        <h2>Items</h2>
        <itemsTable :items="order.orderItems" />
      </div>
    </div>
    <div class="content" v-else>
      <h3>Waiting for a new order to arrive!</h3>
    </div>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import itemsTable from "./items_table.vue";
import chronometer from "./chronometer.vue";

export default {
  data() {
    return {
      order: undefined,
      orderItems: [],
    };
  },
  methods: {
    getCurrentOrder(orderId) {
      axios
        .get(`api/orders/${orderId}`)
        .then((response) => {
          this.order = response.data.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getCurrentOrderId() {
      axios
        .get(`api/employee/${this.$route.params.id}/currentOrder`)
        .then((response) => {
          console.log(response);
          if (response.data) {
            this.getCurrentOrder(response.data.data.id);
          }
        });
    },
    markOrder(value) {
      // 1 - Preparing
      // 2 - Ready
      if (value != "P" && value != "R") {
        return;
      }
      axios
        .patch(`api/orders/${this.order.id}`, {
          status: value,
        })
        .then((response) => {
          let payload = {
            userId: this.order.customer_id,
            status: value,
            orderId: this.order.id,
          };

          this.$socket.emit("order_status", payload);
          this.order.current_status_at = response.data.data.current_status_at;
          this.order.status = value;
          if (value === "R") {
            console.log("entered R");
            this.$toasted
              .show(`Order #${this.order.id} marked as ready!`, {
                type: "success",
              })
              .goAway(3500);
            axios.get("api/orders/preparation/queue").then((response) => {
              if (response.data.data) {
                let order = response.data.data;
                axios
                  .patch(`api/orders/${order.id}`, {
                    prepared_by: this.$store.state.user.id,
                  })
                  .then((response) => {
                    console.log(response);
                    this.$toasted
                      .show(
                        `You've been assigned with a new order (${order.id})`,
                        {
                          type: "info",
                        }
                      )
                      .goAway(3500);
                    this.getCurrentOrder(response.data.data.id);
                  });
              } else {
                this.setCookAvailable();
              }
            });
            return;
          } else {
            this.$toasted
              .show(`Order #${this.order.id} marked as preparing!`, {
                type: "success",
              })
              .goAway(3500);
          }
        });
    },
    setCookAvailable() {
      axios
        .patch(`api/users/${this.$store.state.user.id}`, {
          available: new Boolean(true),
        })
        .then((response) => {
          let payload = {
            userId: this.$store.state.user.id,
            orderId: this.order.id,
          };
          this.order = undefined;
          //this.$socket.emit("order_ready", payload);
          console.log(response.data);
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
  sockets: {
    new_order(orderID) {
      this.getCurrentOrder(orderID);
    },
    order_cancelled(orderID) {
      if (this.order && orderID == this.order.id) {
        this.order = undefined;
        this.orderItems = [];
      }
    },
  },
  mounted() {
    this.getCurrentOrderId();
  },
  components: { navbar, itemsTable, chronometer },
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