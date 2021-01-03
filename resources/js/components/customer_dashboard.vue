<template>
  <div>
    <navbar />
    <h1>Dashboard</h1>
    <div>
      <h4>My Ongoing Orders</h4>
      <orderTable :orders="openOrders" />
      <pagination
        v-if="openOrders.length > 0"
        :data="openOrdersData"
        @pagination-change-page="getOpenResults"
      ></pagination>
    </div>
    <div>
      <h4>My Closed Orders</h4>
      <orderTable :orders="closedOrders" :to-delivery="false" />
      <pagination
        v-if="closedOrders.length > 0"
        :data="closedOpenOrdersData"
        @pagination-change-page="getClosedResults"
      ></pagination>
    </div>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import orderTable from "./order_table.vue";
export default {
  data() {
    return {
      openOrders: [],
      openOrdersData: {},
      closedOrders: [],
      closedOpenOrdersData: {},
    };
  },
  created() {
    axios
      .get(`api/customers/orders/${this.$route.params.id}/open`)
      .then((response) => {
        this.openOrdersData = response.data;
        this.openOrders = this.openOrdersData.data;
      });
    axios
      .get(`api/customers/orders/${this.$route.params.id}/closed`)
      .then((response) => {
        this.closedOpenOrdersData = response.data;
        this.closedOrders = this.closedOpenOrdersData.data;
      });
  },
  methods: {
    getClosedResults(page = 1) {
      let url = `api/customers/orders/${this.$route.params.id}/closed`;
      if (page != 0) {
        url += `?page=${page}`;
        axios.get(url).then((response) => {
          this.closedOpenOrdersData = response.data;
          this.closedOrders = this.closedOpenOrdersData.data;
        });
      }
    },
    getOpenResults(page = 1) {
      let url = `api/customers/orders/${this.$route.params.id}/open`;
      if (page != 0) {
        url += `?page=${page}`;
        axios.get(url).then((response) => {
          this.openOrdersData = response.data;
          this.openOrders = this.openOrdersData.data;
        });
      }
    },
  },
  sockets: {
    order_status_changed(payload) {
      if (payload.status == "D" || payload.status == "C") {
        this.getOpenResults();
        this.getClosedResults();
      }
      let orderId = payload.orderId;
      let status = payload.status;

      let index = this.openOrders.findIndex((order) => order.id == orderId);
      if (index!=-1) {
        this.openOrders[index].status = status;
      }
    },
  },
  components: { navbar, orderTable },
};
</script>

<style>
</style>