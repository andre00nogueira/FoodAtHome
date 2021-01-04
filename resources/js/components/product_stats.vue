<template>
  <div style="margin-top: 5%; display: flex; justify-content: space-between">
    <div style="margin-right: 10%" v-if="!isFetching1">
      <hr />
      <h3>Total Products by Category</h3>
      <hr />
      <doughnutchart
        style="width: 80%; height: 80%"
        :data="productsByCategoryData"
      />
    </div>
    <div style="margin-right: 10%" v-if="!isFetching2">
      <hr />
      <h3>10 Most Sold Products</h3>
      <hr />
      <barchart style="width: 90%; height: 70%" :data="topTenProductsData" />
    </div>
    <div style="margin-right: 10%" v-if="!isFetching3">
      <hr />
      <h3>Amount Sold by Category</h3>
      <hr />
      <doughnutchart
        style="width: 80%; height: 80%"
        :data="salesByCategoryData"
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
      isFetching1: true,
      isFetching2: true,
      isFetching3: true,
      productsByCategoryData: {
        labels: [],
        datasets: [],
      },
      topTenProductsData: {
        labels: [],
        datasets: [
          {
            label: "Quantity",
            backgroundColor: "#f87979",
            data: [],
          },
        ],
      },
      salesByCategoryData: {
        labels: [],
        datasets: [
          {
            backgroundColor: [],
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
  mounted() {
    this.productsByCategory();
    this.topProductsSold();
    this.quantitySoldByCategory();
  },
  methods: {
    productsByCategory() {
      axios.get(`/api/products/total/category`).then((result) => {
        this.productsByCategoryData.labels = Object.keys(result.data);
        this.productsByCategoryData.datasets.push({
          backgroundColor: ["#41B883", "#E46651", "#00D8FF", "#DD1B16"],
          data: Object.values(result.data),
        });
        this.isFetching1 = false;
      });
    },
    topProductsSold() {
      axios.get(`/api/products/total`).then((result) => {
        result.data.forEach((element) => {
          this.topTenProductsData.labels.push(element.name);
          this.topTenProductsData.datasets[0].data.push(element.quantity);
        });
        this.isFetching2 = false;
      });
    },
    quantitySoldByCategory() {
      axios.get(`/api/products/sold/category`).then((result) => {
        result.data.forEach((element) => {
          this.salesByCategoryData.labels.push(element.type);
          this.salesByCategoryData.datasets[0].data.push(element.quantity);
        });
        this.salesByCategoryData.datasets[0].backgroundColor = [
          "#41B883",
          "#E46651",
          "#00D8FF",
          "#DD1B16",
        ];
        this.isFetching3 = false;
      });
    },
  },
};
</script>

<style>
</style>