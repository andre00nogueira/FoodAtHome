<template>
  <div  style="margin-top: 5%">
    <hr>
    <h3>Total Products by Category</h3>
    <hr>
    <doughnutchart
      style="width: 80%; height: 80%"
      v-if="!isFetching"
      :data="productsByCategoryData"
    />
  </div>
</template>

<script>
import doughnutchart from "./graphs/DoughnutChart.vue";
export default {
  data() {
    return {
      isFetching: true,
      productsByCategoryData: {
        labels: ["VueJs", "EmberJs", "ReactJs", "AngularJs"],
        datasets: [],
      },
    };
  },
  components: {
    doughnutchart,
  },
  mounted() {
    this.productsByCategory();
  },
  methods: {
    productsByCategory() {
      axios.get(`/api/products/total/category`).then((result) => {
        console.log(result.data);
        this.productsByCategoryData.labels = Object.keys(result.data);
        this.productsByCategoryData.datasets.push({
          backgroundColor: ["#41B883", "#E46651", "#00D8FF", "#DD1B16"],
          data: Object.values(result.data),
        });
        console.log(this.productsByCategoryData);
        this.isFetching = false;
      });
    },
  },
};
</script>

<style>
</style>