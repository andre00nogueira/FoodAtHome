<script>
import { Bar } from 'vue-chartjs'

export default {
  props: ["data", "options"],
  extends: Bar,
  mounted() {
    this.renderChart(this.data, {responsive: true, maintainAspectRatio: false})
  }
}
</script>