import Doughnut from 'vue-chartjs/es/BaseCharts/Doughnut';
import reactiveProp from 'vue-chartjs/es/mixins/reactiveProp';

export default {
  name: 'module-chart-doughnut',
  extends: Doughnut,
  props: ['options'],
  mixins: [ reactiveProp ],
  mounted() {
    this.renderChart(this.chartData, this.options);
  },
};
