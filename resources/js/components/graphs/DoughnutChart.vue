<script>
import { Doughnut } from "vue-chartjs";

export default {
  props: ["data", "options"],
  extends: Doughnut,
  mounted() {
    // Overwriting base render method with actual data.
    this.renderChart(this.data, {
      responsive: true,
      maintainAspectRatio: false,
    });
  },
};
</script>