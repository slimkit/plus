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
                  <button class="btn btn-primary btn-success btn-sm">导出</button>
                </div>
            </div>
          </div>
          <!-- IEcharts -->
          <div class="panel-body">
             <chart :option="option" :loading="loading" style="height:400px;"></chart>
          </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import IEcharts from 'vue-echarts-v3/src/full.vue';
import _ from 'lodash';
const HomeComponent = {

    components: {
      chart: IEcharts
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

      // echarts params
      option: {
          title: {
            subtext: '打赏金额：0 打赏次数：0',
            left: 50,
          },
          tooltip: {
              trigger: 'axis', 
          },
          legend: {
              data:['打赏次数', '打赏金额'],
              orient: 'horizontal',
              x: 'center',
              y: 'top',
              borderWidth: 1,
              padding: 10,
              itemGap: 20,
          },
          grid: {
              left: '3%',
              right: '4%',
              bottom: '3%',
              containLabel: true
          },
          toolbox: {
              feature: {
                  saveAsImage: {}
              }
          },
          xAxis: {
              type: 'category',
              boundaryGap: false,
              data: [],
          },
          yAxis: {
              type: 'value',
          },
          series: [
              {
                  name: '打赏次数',
                  type: 'line',
                  stack: '总量',
                  data:[]
              },
              {
                  name:'打赏金额',
                  type:'line',
                  stack: '总量',
                  data:[]
              },
          ]
      }


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

          this.loading = false;
          this.initEcharts(response.data);

        }).catch(({ response: { data: { errors = ['打赏统计请求错误'] } = {} } = {} }) => {

        });

      },

      // 初始化 Echarts
      initEcharts (data) {

          let option = this.option;

          option.xAxis.data = [];
          option.series[0].data = [];
          option.series[1].data = [];

          let total_count = 0;
          let total_amount = 0;

          _.forEach(data, function(n, key) {
 
            total_count  = total_count + data[key].reward_count;
            total_amount = parseInt(total_amount) + parseInt(data[key].reward_amount);

            option.xAxis.data.push(data[key].reward_date);
            option.series[0].data.push(data[key].reward_count);
            option.series[1].data.push(data[key].reward_amount/100);

          });

          option.title.subtext  = '打赏金额：'+ (total_amount/100) +'元';
          option.title.subtext += ' 打赏次数：'+total_count+'次';
      },

      getQueryParams () {
        let query = '?';
        let filter = this.filter;

        query += 'type=' + filter.type;
        query += '&start=' + (filter.start ? filter.start : '');
        query += '&end=' + (filter.end ? filter.end : '');
        
        return query;
      },
    },

    created () {
      this.getRewardStatistics();
    },
};

export default HomeComponent;
</script>
