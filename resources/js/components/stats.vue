<template>
  <div>
    <navbar />
    <h2>Statistics</h2>
    <div style="margin-top:5%;display:flex;justify-content:space-evenly">
      <button class="btn btn-primary" style="padding-left:5%;padding-right:5%" @click="showStats('P')">Products</button>
      <button class="btn btn-primary" style="padding-left:5%;padding-right:5%" @click="showStats('O')">Orders</button>
      <button class="btn btn-primary" style="padding-left:5%;padding-right:5%" @click="showStats('C')">Company</button>
    </div>
    <div class="content" style="display: flex">
      <productstats v-if="dataToShow == 'P'" />
      <orderstats v-if="dataToShow == 'O'" />
      <companystats v-if="dataToShow == 'C'" />
    </div>
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import orderstats from "./order_stats.vue";
import productstats from "./product_stats.vue";
import companystats from "./company_stats.vue";

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
    productstats,
    navbar,
    orderstats,
    companystats,
  },
};
</script>
<style>
</style>