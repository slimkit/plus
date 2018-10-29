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
                  <option v-for="type in reward_types" :key="type.name" :value="type.name">{{ type.alias }}</option>
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
                <button class="btn btn-default">确认</button>
              </div>
              <div class="form-group pull-right">
                <a href="javascript:;" class="btn btn-success">导出</a>
              </div>
            </div>
          </div>
          <!-- charts -->
          <div class="panel-body">
          <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：18108035545。</p>
          </div>
        </div>
    </div>
</template>

<script>

const HomeComponent = {
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


};

export default HomeComponent;
</script>
