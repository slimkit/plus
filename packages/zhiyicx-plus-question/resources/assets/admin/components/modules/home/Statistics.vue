<template>

  <div class="panel panel-default">

    <div class="panel-heading">
      <div class="row">
        <div class="col-xs-6 text-left">
          数据统计 &nbsp;
          <ui-loading v-show="loading"></ui-loading>
        </div>
        <div class="col-xs-6 text-right">
          <div class="btn-group btn-group-xs" role="group">
            <button type="button" class="btn btn-default" :disabled="type === 'all'" @click.prevent="handle('all')">总数据</button>
            <button type="button" class="btn btn-default" :disabled="type === 'today'" @click.prevent="handle('today')">今日</button>
            <button type="button" class="btn btn-default" :disabled="type === 'yesterday'" @click.prevent="handle('yesterday')">昨日</button>
            <button type="button" class="btn btn-default" :disabled="type === 'week'" @click.prevent="handle('week')">一周</button>
          </div>
        </div>
      </div>
    </div>

    <div class="panel-body">
      <div class="row">
      
          <!-- 问题数 & 回答数 -->
          <div class="col-sm-4">
            问题 & 回答：
            <chart-horizontal-bar :chart-data="questionAndAnswer"></chart-horizontal-bar>
          </div>

          <!-- 邀请问题数 & 公开悬赏问题数 -->
          <div class="col-sm-4">
            邀请 & 悬赏：
            <chart-pie :chart-data="question"></chart-pie>
          </div>

          <!-- 邀请悬赏金额 & 公开悬赏金额 & 围观总额 -->
          <div class="col-sm-4">
            金额统计：
            <chart-doughnut :chart-data="amount"></chart-doughnut>
          </div>

      </div>

      <ui-alert :type="message.type" v-show="message.open">
        {{ message.data | plusMessageFirst('获取失败，请刷新重试!') }}
      </ui-alert>

    </div>

  </div>
</template>

<script>
import HorizontalBarChart from '../HorizontalBarChart';
import PieChart from '../PieChart';
import DoughnutChart from '../DoughnutChart';
import { admin } from '../../../axios';
import { money as moneyFilter, thousands as thousandsFilter } from '../../../filters';
export default {
  name: 'module-home-statistic',
  components: {
    'chart-horizontal-bar': HorizontalBarChart,
    'chart-pie': PieChart,
    'chart-doughnut': DoughnutChart
  },

  computed: {
    questionAndAnswer() {
      const { statistics: { [this.type]: { question = 0, answer = 0 } = {} } = {} } = this.$store.state;
      return { datasets: [
        {
          label: `问题量：${thousandsFilter(question)}`,
          backgroundColor: '#f87979',
          data: [ question ]
        },
        {
          label: `回答量：${thousandsFilter(answer)}`,
          backgroundColor: '#2a88bd',
          data: [ answer ]
        }
      ] };
    },
    question() {
      const { statistics: { [this.type]: { invitation = 0, public: publicQuestion = 0 } = {} } = {} } = this.$store.state;
      return {
        labels: [
          `邀请问题：${thousandsFilter(invitation)}`,
          `公开悬赏问题：${thousandsFilter(publicQuestion)}`
        ],
        datasets: [{
          backgroundColor: [ '#00D8FF', '#41B883' ],
          data: [ invitation, publicQuestion ]
        }]
      };
    },
    amount() {
      const { statistics: { [this.type]: { iamount = 0, pamount = 0, reward = 0 } = {} } = {} } = this.$store.state;
      return {
        labels: [
          `邀请悬赏赏金：¥ ${moneyFilter(iamount / 100)}`,
          `公开悬赏赏金：¥ ${moneyFilter(pamount / 100)}`,
          `围观金额：¥ ${moneyFilter(reward / 100)}`
        ],
        datasets: [{
          backgroundColor: [ '#00D8FF', '#41B883', '#E46651' ],
          data: [ iamount / 100, pamount / 100, reward / 100 ]
        }]
      };
    }
  },

  data: () => ({
    type: 'all',
    loading: false,
    message: {
      open: false,
      type: '',
      data: {}
    }
  }),

  methods: {
    publishMessage (data, type, ms = 3000) {
      this.message = { open: true, data, type };
      setTimeout(() => {
        this.message.open = false;
      }, ms);
    },

    handle(type) {
      this.type = type;
      this.loading = true;

      this.$store.dispatch('statistics', payload => admin.get('/statistics', {
        validateStatus: status => status === 200,
        params: { type }
      }).then(({ data = {} }) => {
        this.loading = false;
        payload(type, data);
      }).catch(({ response: { data = {} } = {} } = {}) => {
        this.loading = false;
        this.publishMessage(data, 'danger');
      }));
    }
  },

  created() {
    this.handle('all');
  }
};
</script>
