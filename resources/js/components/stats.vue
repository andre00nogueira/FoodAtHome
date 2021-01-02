<template>
  <div>
    <navbar />
    <h2>Statistics</h2>
    <div class="content" style="display: flex">
      <linechart style="width: 30%; height: 30%" />
      <radarchart style="width: 30%; height: 30%" />
      <doughnutchart style="width: 30%; height: 30%" />
      <barchart style="width: 30%; height: 30%" />
    </div>
  </div>
</template>

<script>
import barchart from "./graphs/BarChart.vue";
import doughnutchart from "./graphs/DoughnutChart.vue";
import linechart from "./graphs/LineChart.vue";
import radarchart from "./graphs/RadarChart.vue";

export default {
  components: { linechart, radarchart, doughnutchart, barchart },
};
</script>
<style>
</style>