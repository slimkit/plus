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
                <!-- 关键词 -->
                <div class="form-group">
                  <input type="text" class="form-control" v-model="filter.keyword" placeholder="打赏用户名/ID">
                </div>
                <!-- 类型 -->
                <div class="form-group">
                  <select class="form-control" v-model="filter.type">
                    <option v-for="type in reward_types" :key="type.name" :value="type.name">{{ type.alias }}</option>
                  </select>
                </div>
                <!-- 时间段 -->
                <div class="form-group">
                  <div class="input-group">
                    <input type="date" class="form-control" v-model="filter.start">
                    <div class="input-group-addon">-</div>
                    <input type="date" class="form-control" v-model="filter.end">
                  </div>
                </div>
                <!-- 搜索 -->
                <div class="form-group">
                  <button class="btn btn-default">搜索</button>
                </div>
                <!-- 导出 -->
                <a href="javascript:;" class="btn btn-success">导出</a>
              </div>
            </div>
            <!-- 添加广告 -->
            <div class="panel-body">
              <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>打赏用户</th>
                            <th>被打赏用户</th>
                            <th>打赏金额(元)</th>
                            <th>打赏应用</th>
                            <th>打赏时间</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：17311245680。</p>
            </div>
        </div>
    </div>
</template>
<script>
const ListComponent = {
    data: () => ({
      loadding: false,
      rewards: [],
      total: 0,
      filter: {
        type: '',
        start: '',
        end: '',
        keyword: '',
      },
      message: {
        error: null,
        success: null,
      },
      reward_types: [
        { name: '', alias: '全部' },
        { name: 'feeds', alias: '动态打赏' },
        { name: 'news', alias: '资讯打赏' },
        { name: 'users', alias: '用户打赏' },
        { name: 'question-answers', alias: '问答打赏' },
      ],
    }),
    watch: {
      '$route': function ($route) {
        this.total = 0;
        this.getRewards({ ...$route.query });
      },
    },
    methods: {
      getRewards (query = {}) {
        this.rewards = {};
        this.loadding = true;
        request.get(
          createRequestURI('rewards'),
          {
            validateStatus: status => status === 200,
            params: { ...query, limit: 15 },
          },
        ).then(({ data = [], headers: { 'x-reward-total': total } }) => {
          this.loadding = false;
          this.total = parseInt(total);
          this.rewards = data;
        }).catch(({ response: { data: { errors = ['加载失败'] } = {} } = {} }) => {
          this.loadding = false;
          this.message.error = plusMessageFirst(errors);
        });
      },
      offsetPage(offset) {
        return { path: '/reward/list', query: { ...this.filter, offset } };
      },
    },
    created () {
      this.getRewards(this.$route.query);
    },
};
export default ListComponent;
</script>
