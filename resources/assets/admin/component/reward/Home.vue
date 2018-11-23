<template>
    <div class="container-fluid" style="margin:15px;">
        <div v-show="message.success" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.success }}
        </div>
        <div v-show="message.error" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.error }}
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="form-inline">
              <!-- 打赏类型 -->
              <div class="form-group">
                <select class="form-control" v-model="filter.type">
                  <option v-for="type in reward_types" :value="type.name" :key="type.name">{{ type.alias }}</option>
                </select>
              </div>
              <!-- 打赏类型 -->
              <div class="form-group">
                <select class="form-control" v-model="filter.scope">
                  <option value="today">今日</option>
                  <option value="week">近七天</option>
                  <option value="custom">自定义时间段</option>
                </select>
              </div>
              <!-- 时间范围 -->
              <div class="form-group">
                <div class="input-group">
                  <input type="date" class="form-control" v-model="filter.start" :disabled="disabled">
                  <div class="input-group-addon">-</div>
                  <input type="date" class="form-control" v-model="filter.end" :disabled="disabled">
                </div>
                <button class="btn btn-default" @click="getRewardStatistics">确认</button>
              </div>
              <div class="form-group pull-right">
                <a :href="exportUrl" class="btn btn-success">导出</a>
              </div>
            </div>
          </div>
          <!-- charts -->
          <div class="panel-body">
             <chart :option="option" :loading="loading" style="height:500px;"></chart>
          </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import { plusMessageFirst } from '../../filters';
import IEcharts from 'vue-echarts-v3/src/lite';
import 'echarts/lib/chart/line';
import 'echarts/lib/component/grid';
import 'echarts/lib/component/legend';
import 'echarts/lib/component/tooltip';
import 'echarts/lib/component/title';
import 'echarts/lib/component/toolbox';
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
        { name: 'news', alias: '资讯打赏' },
        { name: 'users', alias: '用户打赏' },
        { name: 'question-answers', alias: '问答打赏' }
      ],
      message: {
        error: null,
        success: null,
      },
      filter: {
        scope: 'today',
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
            borderWidth: 2,
            data:[]
          },
          {
            name:'打赏金额',
            type:'line',
            stack: '总量',
            borderWidth: 2,
            data:[]
          },
        ]
      }
    }),
    computed: {
      disabled () {
        if (this.filter.scope == 'custom') {
          return false;
        } else {
          return true;
        }
      },
      exportUrl () {
        let url = '/admin/rewards/export?export_type=statistic';
        let filter = this.filter;
  
        url  += '&type=' + filter.type;
        if (filter.scope == 'custom') {
          url += '&start=' + (filter.start ? filter.start : '');
          url += '&end=' + (filter.end ? filter.end : '');
        } else {
          url += '&scope=' + filter.scope;
        }
        return url;
      }
    },
    watch: {
      'filter.type'() {
        this.getRewardStatistics();
      },
      'filter.scope'() {
        this.getRewardStatistics();
      }
    },
    methods: {
      getRewardStatistics () {
        this.loading = true;
        request.get(
          createRequestURI('rewards/statistics' + this.getQueryParams()),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loading = false;
          this.initEcharts(response.data);
        }).catch(({ response: { data: { errors = ['打赏统计请求错误'] } = {} } = {} }) => {
          this.message.error = plusMessageFirst(errors);
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
            total_amount = parseInt(data[key].reward_amount) + parseInt(total_amount);

            option.xAxis.data.push(data[key].reward_date);
            option.series[0].data.push(data[key].reward_count);
            option.series[1].data.push(data[key].reward_amount);

          });

          option.title.subtext  = '打赏金额：'+ (total_amount/100) +' 元';
          option.title.subtext += ' 打赏次数：'+total_count+' 次';
      },
      getQueryParams () {
        let query = '?';
        let filter = this.filter;
        query += 'type=' + filter.type;
        if (filter.scope == 'custom') {
          query += '&start=' + (filter.start ? filter.start : '');
          query += '&end=' + (filter.end ? filter.end : '');
        } else {
          query += '&scope=' + filter.scope;
        }
        return query;
      },
    },

    created () {
      this.getRewardStatistics();
    },
};

export default HomeComponent;
</script>