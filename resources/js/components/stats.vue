<template>
  <div>
    <navbar />
    <h2>Statistics</h2>
    <button class="btn btn-primary" @click="showStats('P')">Products</button>
    <button class="btn btn-secondary" @click="showStats('O')">Orders</button>
    
    <div class="content" style="display: flex">
      <productstats v-if="dataToShow == 'P'" />
      <orderstats v-if="dataToShow == 'O'" />
    </div>
    
  </div>
</template>

<script>
import navbar from "./navbar.vue";
import orderstats from './order_stats.vue';
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
    productstats,
    navbar,
    orderstats,
  },
};
</script>
<style>
</style>