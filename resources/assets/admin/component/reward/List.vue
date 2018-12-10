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
                  <router-link class="btn btn-default" tag="button" :to="{ path: '/reward/list', query: searchQuery }">
                    搜索
                  </router-link>
                </div>
                <!-- 导出 -->
                <a :href="exportUrl" class="btn btn-success">导出</a>
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
                        <!-- 加载 -->
                        <table-loading :loadding="loadding" :colspan-num="6"></table-loading>
                        <tr v-for="reward in rewards" :key="reward.id">
                          <td>{{ reward.id }}</td>
                          <td>{{ reward.user ? reward.user.name : '未知' }}</td>
                          <td>{{ reward.target ? reward.target.name : '未知' }}</td>
                          <td>{{ reward.amount / 100 }}</td>
                          <td v-if="reward.rewardable_type=='feeds'">动态</td>
                          <td v-else-if="reward.rewardable_type=='news'">资讯</td>
                          <td v-else-if="reward.rewardable_type=='users'">用户</td>
                          <td v-else-if="reward.rewardable_type=='question-answers'">问答</td>
                          <td v-else>未知</td>
                          <td>{{ reward.created_at | localDate }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- 分页 -->
                <div class="text-center">
                  <offset-paginator class="pagination" :total="total" :offset="offset" :limit="15">
                    <template slot-scope="pagination">
                      <li :class="(pagination.disabled ? 'disabled': '') + (pagination.currend ? 'active' : '')">
                        <span v-if="pagination.disabled || pagination.currend">{{ pagination.page }}</span>
                        <router-link v-else :to="offsetPage(pagination.offset)">{{ pagination.page }}</router-link>
                      </li>
                    </template>
                  </offset-paginator>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import { plusMessageFirst } from '../../filters';

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
    computed: {
      exportUrl () {
        return '/admin/rewards/export?export_type=list' + $.param(this.filter);
      },
      offset () {
        const { query: { offset = 0 } } = this.$route;
        return parseInt(offset);
      },
      searchQuery () {
        return { ...this.filter, offset: 0 };
      },
    },
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

