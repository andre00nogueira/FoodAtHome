<template>
  <div
    class="col"
    style="margin-top: 5%; display: flex; justify-content: space-between"
  >
    <div>
      <div style="margin-right: 10%" v-if="!isFetching1">
        <hr />
        <h3>Orders Per Year</h3>
        <hr />
        <barchart style="width: 100%; height: 90%" :data="ordersPerYear" />
      </div>
      <div style="margin-right: 10%" v-if="!isFetching3">
        <hr />
        <h3>Sales Per Year</h3>
        <hr />
        <barchart style="width: 100%; height: 90%" :data="salesPerYear" />
      </div>
    </div>
    <div>
      <div style="margin-right: 10%" v-if="!isFetching2">
        <hr />
        <h3>Orders Per Month</h3>
        <hr />
        <barchart style="width: 120%; height: 90%" :data="ordersPerMonth" />
      </div>

      <div style="margin-right: 10%" v-if="!isFetching4">
        <hr />
        <h3>Sales Per Month</h3>
        <hr />
        <barchart style="width: 120%; height: 90%" :data="salesPerMonth" />
      </div>
    </div>
  </div>
</template>

<script>
import barchart from "./graphs/BarChart.vue";
export default {
  data() {
    return {
      isFetching1: true,
      isFetching2: true,
      isFetching3: true,
      isFetching4: true,
      ordersPerYear: {
        labels: [],
        datasets: [
          {
            label: "Orders per Year",
            backgroundColor: "#f87979",
            data: [],
          },
        ],
      },
      ordersPerMonth: {
        labels: [],
        datasets: [
          {
            label: "Orders per Month",
            backgroundColor: "#f87979",
            data: [],
          },
        ],
      },
      salesPerYear: {
        labels: [],
        datasets: [
          {
            label: "Sales per Year",
            backgroundColor: "#f87979",
            data: [],
          },
        ],
      },
      salesPerMonth: {
        labels: [],
        datasets: [
          {
            label: "Sales per Month",
            backgroundColor: "#f87979",
            data: [],
          },
        ],
      },
    };
  },
  components: {
    barchart,
  },
  mounted() {
    this.ordersYear();
    this.ordersMonth();
    this.salesYear();
    this.salesMonth();
  },
  methods: {
    ordersYear() {
      return axios.get("/api/orders/quantity/year").then((result) => {
        result.data.forEach((element) => {
          this.ordersPerYear.labels.push(element.year);
          this.ordersPerYear.datasets[0].data.push(element.quantity);
        });
        this.isFetching1 = false;
      });
    },
    ordersMonth() {
      return axios.get("/api/orders/quantity/month").then((result) => {
        result.data.forEach((element) => {
          this.ordersPerMonth.labels.push(element.month);
          this.ordersPerMonth.datasets[0].data.push(element.quantity);
        });
        this.isFetching2 = false;
      });
    },
    salesYear() {
      return axios.get("/api/sales/quantity/year").then((result) => {
        result.data.forEach((element) => {
          this.salesPerYear.labels.push(element.year);
          this.salesPerYear.datasets[0].data.push(element.quantity);
        });
        this.isFetching3 = false;
      });
    },
    salesMonth() {
      return axios.get("/api/sales/quantity/month").then((result) => {
        result.data.forEach((element) => {
          this.salesPerMonth.labels.push(element.month);
          this.salesPerMonth.datasets[0].data.push(element.quantity);
        });
        this.isFetching4 = false;
      });
    },
  },
};
</script>

<style>
</style>