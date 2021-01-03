<template>
  <div style="margin-top: 5%; display: flex; justify-content: space-between">
    <div style="margin-right: 10%">
      <hr />
      <h3>Total Products by Category</h3>
      <hr />
      <doughnutchart
        style="width: 85%; height: 85%"
        v-if="!isFetching"
        :data="productsByCategoryData"
      />
    </div>
    <div style="margin-right: 10%">
      <hr />
      <h3>10 Most Sold Products</h3>
      <hr />
      <barchart
        style="width: 100%; height: 80%"
        v-if="!isFetching"
        :data="topTenProducts"
      />
    </div>
    <div style="margin-right: 10%">
      <hr />
      <h3>Total Products by Category</h3>
      <hr />
      <barchart
        style="width: 100%; height: 80%"
        v-if="!isFetching"
        :data="productsByCategoryData"
      />
    </div>
  </div>
</template>

<script>
import doughnutchart from "./graphs/DoughnutChart.vue";
import barchart from "./graphs/BarChart.vue";
export default {
  data() {
    return {
      isFetching: true,
      productsByCategoryData: {
        labels: [],
        datasets: [],
      },
      topTenProducts: {
        labels: [],
        datasets: [
          {
            label: "Quantity",
            backgroundColor: "#f87979",
            data: [],
          },
        ],
      },
    };
  },
  components: {
    doughnutchart,
    barchart,
  },
  async mounted() {
    await this.productsByCategory();
    await this.topProductsSold();
    this.isFetching = false;
  },
  methods: {
    productsByCategory() {
      return axios.get(`/api/products/total/category`).then((result) => {
        this.productsByCategoryData.labels = Object.keys(result.data);
        this.productsByCategoryData.datasets.push({
          backgroundColor: ["#41B883", "#E46651", "#00D8FF", "#DD1B16"],
          data: Object.values(result.data),
        });
      });
    },
    topProductsSold() {
      return axios.get(`/api/products/total`).then((result) => {
        console.log(result);
        result.data.forEach((element) => {
          this.topTenProducts.labels.push(element.name);
          this.topTenProducts.datasets[0].data.push(element.quantity);
        });
      });
    },
  },
};
</script>

<style>
</style>