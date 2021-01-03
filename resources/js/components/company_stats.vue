<template>
  <div style="margin-top: 5%; display: flex; justify-content: space-between">
    <div style="margin-right: 10%" v-if="!isFetching1">
      <hr />
      <h3>Average Time Preparing Delivery</h3>
      <hr />
      <barchart style="width: 90%; height: 70%" :data="companyAverageTimes" />
    </div>
    <div style="margin-right: 10%" v-if="!isFetching2">
      <hr />
      <h3>Orders Status</h3>
      <hr />
      <doughnutchart style="width: 80%; height: 80%" :data="ordersStatus" />
    </div>
  </div>
</template>

<script>
import barchart from "./graphs/BarChart.vue";
import doughnutchart from "./graphs/DoughnutChart.vue";
export default {
  data() {
    return {
      isFetching1: true,
      isFetching2: true,
      companyAverageTimes: {
        labels: [],
        datasets: [
          {
            label: "Average Time (seconds)",
            backgroundColor: "#f87979",
            data: [],
          },
        ],
      },
      ordersStatus: {
        labels: [],
        datasets: [],
      },
    };
  },
  components: {
    barchart,
    doughnutchart,
  },
  mounted() {
    this.averageTimes();
    this.orders();
  },
  methods: {
    averageTimes() {
      return axios.get("/api/orders/average/time").then((result) => {
        /*this.companyAverageTimes.labels = Object.keys(result.data);
        this.companyAverageTimes.datasets.push({
          backgroundColor: ["#41B883", "#E46651", "#00D8FF"],
          data: Object.values(result.data),
        });*/

        this.companyAverageTimes.labels = [
          "Preparation Time",
          "Delivery Time",
          "Total Time",
        ];
        this.companyAverageTimes.datasets[0].data.push(
          result.data.preparation_time
        );
        this.companyAverageTimes.datasets[0].data.push(
          result.data.delivery_time
        );
        this.companyAverageTimes.datasets[0].data.push(result.data.total_time);
        /*result.forEach((element) => {
          
        });*/
        this.isFetching1 = false;
      });
    },
    orders() {
      return axios.get(`/api/orders/total/status`).then((result) => {
        this.ordersStatus.labels = ["Delivered", "Cancelled", "Ongoing"];
        let aux = [
          result.data.delivered,
          result.data.cancelled,
          result.data.ongoing,
        ];
        this.ordersStatus.datasets.push({
          backgroundColor: ["#41B883", "#E46651", "#00D8FF"],
          data: aux,
        });
        this.isFetching2 = false;
      });
    },
  },
};
</script>

<style>
</style>