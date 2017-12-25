import HorizontalBar from 'vue-chartjs/es/BaseCharts/HorizontalBar';
import reactiveProp from 'vue-chartjs/es/mixins/reactiveProp';

export default {
  name: 'module-chart-horizontal-bar',
  extends: HorizontalBar,
  props: ['options'],
  mixins: [ reactiveProp ],
  mounted() {
    this.renderChart(this.chartData, this.options);
  },
};
