import Pie from 'vue-chartjs/es/BaseCharts/Pie';
import reactiveProp from 'vue-chartjs/es/mixins/reactiveProp';

export default {
  name: 'module-chart-pie',
  extends: Pie,
  props: ['options'],
  mixins: [ reactiveProp ],
  mounted() {
    this.renderChart(this.chartData, this.options);
  },
};
