<template>
    <div style="padding: 15px;">
        <div v-show="message.success" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ message.success }}
        </div>
        <div v-show="message.error" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ message.error }}
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="form-inline">
              <div class="form-group">
                <label>打赏类型：</label>
                <select class="form-control" v-model="filter.type">
                  <option v-for="type in reward_types" :value="type.name">{{ type.alias }}</option>
                </select>
                </div>
                <div class="form-group">
                  <label>时间范围：</label>
                  <input type="date" class="form-control" v-model="filter.start">
                  <input type="date" class="form-control" v-model="filter.end">
                  <button class="btn btn-primary btn-sm" @click="getRewardStatistics">确认</button>
                </div>
                <div class="form-group pull-right">
                  <button class="btn btn-primary btn-success btn-sm" @click="test">导出</button>
                </div>
            </div>
          </div>
          <!-- IEcharts -->
          <div class="panel-body">
            <chart :chart-data="chartData" :options="chartOptions" :height="200"></chart>
          </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import _ from 'lodash';
import LineChart from './LineChart';
const HomeComponent = {

    components: {
      chart: LineChart
    },
    data: () => ({     

      loading: true,

      reward_types: [
        { name: '', alias: '全部' },
        { name: 'feeds', alias: '动态打赏' },
        { name: 'news', alias: '咨询打赏' },
        { name: 'users', alias: '用户打赏' },
        { name: 'question-answers', alias: '问答打赏' }
      ],  

      message: {
        error: null,
        success: null,
      },

      filter: {
        type: '',
        date_start: '',
        date_end: '',
      },

      chartData: null,

      chartOptions: null,

    }),

    watch: {
      deep: true,
      'filter.type': {
        handler: function (val, oldVal) {
          this.getRewardStatistics();
        },
      }
    },

    methods: {  
      
      getRewardStatistics () {

        request.get(
          createRequestURI('rewards/statistics' + this.getQueryParams()),
          { validateStatus: status => status === 200 }
        ).then(response => {

          this.initCharts(this.handleData(response.data));

        }).catch(({ response: { data: { errors = ['打赏统计请求错误'] } = {} } = {} }) => {

        });

      },

      handleData (data) {

          let labels = [];
          let counts  = [];
          let amounts = [];
          let totalCount = 0;
          let totalAmount = 0;

          _.forEach(data, function(n, key) {
            labels.push(data[key].reward_date);
            counts.push(data[key].reward_count);
            amounts.push(data[key].reward_amount/100);
          });

          let res = { labels: labels, counts: counts, amounts: amounts };

          return res;

      },

      // 初始化 Charts
      initCharts (data) {
        // data
        this.chartData = {
          labels: data.labels,
          datasets: [
            {
                label: '打赏次数',
                data: data.counts,
                borderWidth: 2,
                backgroundColor: '#ff6666',
                borderColor: '#bf5329',
            },
            {
                label: '打赏金额',
                data: data.amounts,
                borderWidth: 2,
                backgroundColor: '#33ccff',
                borderColor: '#3097D1'
            }
          ]
        },
        // options
        this.chartOptions = {

          title: {
            display: true,
            text: '打赏统计',
            position: 'top',
            fontSize: 14,
          },

          tooltips: {
            custom: function (tooltipModel) {
              // return 1111;
            }
          }
        } 
      },

      getQueryParams () {
        let query = '?';
        let filter = this.filter;

        query += 'type=' + filter.type;
        query += '&start=' + (filter.start ? filter.start : '');
        query += '&end=' + (filter.end ? filter.end : '');
        
        return query;
      },

      test () {
        this.chartDatas.labels.push('11111111');
      },
    },

    created () {
      this.getRewardStatistics();
    },

};

export default HomeComponent;
</script>

