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
                <!-- 关键词 -->
                <div class="form-group">
                  <input type="text" class="form-control" v-model="filter.keyword" placeholder="打赏用户名/ID">
                </div>
                <!-- 类型 -->
                <div class="form-group">
                  <select class="form-control" v-model="filter.type">
                    <option v-for="type in reward_types" :value="type.name">{{ type.alias }}</option>
                  </select>
                </div>
                <!-- 时间段 -->
                <div class="form-group">
                  <input type="date" class="form-control" v-model="filter.start">
                  <label>-</label>
                  <input type="date" class="form-control" v-model="filter.end">
                </div>
                <!-- 搜索 -->
                <div class="form-group">
                  <button class="btn btn-default" @click.prevent="search">搜索</button>
                </div>
                <!-- 导出 -->
                <a :href="exportUrl" target="_self" class="btn btn-success">导出</a>
                <div class="input-group pull-right">
                    <ul class="pagination" style="margin: 0;">
                      <li :class="paginate.current_page <= 1 ? 'disabled' : null">
                        <a href="javascript:;" aria-label="Previous" @click.stop.prevent="prevPage">
                          <span aria-hidden="true">&laquo;</span>
                        </a>
                      </li>
                      <li :class="paginate.current_page >= paginate.last_page ? 'disabled' : null">
                        <a href="javascript:;" aria-label="Next" @click.stop.prevent="nextPage">
                          <span aria-hidden="true">&raquo;</span>
                        </a>
                      </li>
                    </ul>
                </div>
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
                        <table-loading :loadding="loadding" colspanNum="6"></table-loading>
                        <tr v-for="reward in rewards">
                          <td>{{ reward.id }}</td>
                          <td>{{ reward.user.name }}</td>
                          <td>{{ reward.target.name }}</td>
                          <td>{{ reward.amount/100 }}</td>
                          <td v-if="reward.rewardable_type=='feeds'">动态</td>
                          <td v-else-if="reward.rewardable_type=='news'">咨询</td>
                          <td v-else-if="reward.rewardable_type=='users'">用户</td>
                          <td v-else-if="reward.rewardable_type=='question-answers'">问答</td>
                          <td> <local-date :utc="reward.created_at"/> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import plusMessageBundle from 'plus-message-bundle';
import tableLoading from '../common/TableLoading';
const ListComponent = {
    components: {
      tableLoading
    },
    data: () => ({
      loadding: false,
      rewards: {},
      reward_types: [
        { name: '', alias: '全部' },
        { name: 'feeds', alias: '动态打赏' },
        { name: 'news', alias: '咨询打赏' },
        { name: 'users', alias: '用户打赏' },
        { name: 'question-answers', alias: '问答打赏' },
      ],
      paginate: {
        current_page: 1,
        last_page: 0,
        per_page: 20,
      },
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
    }),
    computed: {
      exportUrl () {
        let url = '/admin/rewards/export?export_type=list';
        let filter = this.filter;
        url += '&type=' + filter.type;
        url += '&start=' + filter.start;
        url += '&end=' + filter.end;
        url += '&keyword=' + filter.keyword;
        return url;
      }
    },
    watch: {
      'filter.type'() {
        this.paginate.current_page = 1;
        this.getRewards();
      },
      'paginate.current_page'() {
        this.getRewards();
      }
    },
    methods: {
      getRewards () {
        this.rewards = {};
        this.loadding = true;
        request.get(
          createRequestURI('rewards' + this.getQueryParams()),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;

          let { 
            data: data, 
            current_page: current_page, 
            last_page: last_page, 
          } = response.data;
          this.paginate.current_page = current_page;
          this.paginate.last_page = last_page;
          this.rewards = data;

        }).catch(({ response: { data: { errors = ['加载失败'] } = {} } = {} }) => {
          this.loadding = false;
          let Message = new plusMessageBundle(errors);
          this.message.error = Message.getMessage();
        });
      },
      search () {
        this.paginate.current_page = 1;
        this.getRewards();
      },
      nextPage () {
        if (this.paginate.last_page > this.paginate.current_page) {
          this.paginate.current_page += 1;
        } 
      },
      prevPage () {
        if (this.paginate.current_page > 1) {
          this.paginate.current_page -= 1;
        } 
      },
      getQueryParams () {
        let filter = this.filter;
        let paginate = this.paginate;
        let params = '?';

        params += 'keyword=' + filter.keyword;
        params += '&type=' + filter.type;
        params += '&end=' + filter.end;
        params += '&start=' + filter.start;
        params += '&per_page=' + paginate.per_page;
        params += '&page=' + paginate.current_page;

        return params; 
      },
    },
    created () {
      this.getRewards();
    },
};
export default ListComponent;
</script>

