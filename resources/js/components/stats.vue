<template>
  <div>
    <navbar />
    <h2>Statistics</h2>
    <button class="btn btn-link" @click="showStats('P')">Products</button>
    <button class="btn btn-link" @click="showStats('O')">Orders</button>
    <productstats v-if="dataToShow == 'P'" />
    <div class="content" style="display: flex">
      <linechart style="width: 30%; height: 30%" />
      <radarchart style="width: 30%; height: 30%" />
      
      <barchart style="width: 30%; height: 30%" />
    </div>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import barchart from "./graphs/BarChart.vue";

import linechart from "./graphs/LineChart.vue";
import radarchart from "./graphs/RadarChart.vue";
import productstats from "./product_stats.vue";

export default {
  data() {
    return {
      dataToShow: "P",
    };
  },
  methods: {
    showStats(type) {
      this.dataToShow = type;
    },
  },
  components: {
    linechart,
    radarchart,
    barchart,
    productstats,
    navbar,
  },
};
</script>
<style>
</style>