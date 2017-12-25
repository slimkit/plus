<template>
  <div class="panel panel-default">

    <!-- Title -->
    <div class="panel-heading">筛选条件</div>

    <!-- Body -->
    <div class="panel-body">
      <div class="form-horizontal">

        <!-- 问题 ID -->
        <div class="form-group">
          <label class="col-sm-2 control-label">问题 ID</label>
          <div class="col-sm-2">
            <input type="number" class="form-control" placeholder="问题 ID" min="1" v-model.number="localQuery.question">
          </div>
          <div class="col-sm-4">
            <module-search-question :handle-selected="handleSelectedQuestion"></module-search-question>
          </div>
          <span class="col-sm-4 help-block">
            选择所属问题，如果不知道 ID，可以使用搜索栏检索。
          </span>
        </div>

        <!-- 申请者 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">申请者</label>
          <div class="col-sm-2">
            <input type="number" class="form-control" placeholder="申请者" min="1" v-model.number="localQuery.user">
          </div>
          <div class="col-sm-4">
            <module-search-user :handle-selected="handleSelectedUser"></module-search-user>
          </div>
          <span class="col-sm-4 help-block">
            选择申请精选的用户，不知道用户 ID 可以进行检索。
          </span>
        </div>

        <!-- 时间 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">类型</label>
          <div class="col-sm-6">
            <select name="" class="form-control" v-model="localQuery.status">
              <option :value="status.val" v-for="status in statuss">{{ status.label }}</option>
            </select>
          </div>
          <span class="col-sm-4 help-block">
            选择申请精选的状态
          </span>
        </div>

        <!-- 时间 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">申请时间</label>
          <div class="col-sm-6">
              
            <div class="input-group">
              <input type="date" class="form-control" v-model="localQuery.start_date">
              <span class="input-group-addon">-</span>
              <input type="date" class="form-control" v-model="localQuery.end_date">
            </div>

          </div>
        </div>

        <!-- 提交按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button v-if="searching" class="btn btn-primary" disabled="disabled">
              <ui-loading></ui-loading>
              搜索中...
            </button>
            <router-link v-else tag="a" :to="{ path: '/excellences', query: getSearchQuery }" class="btn btn-primary">
              搜索
            </router-link>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>
import lodash from 'lodash';
import SearchQuestion from '../SearchQuestion';
import SearchUser from '../SearchUser';
const defaultQueryValues = {
  question: '',
  user: '',
  start_date: '',
  end_date: '',
  status: null,
};
export default {
  name: 'module-excellence-search',
  components: {
    [SearchQuestion.name]: SearchQuestion,
    [SearchUser.name]: SearchUser,
  },
  props: {
    searching: { type: Boolean, required: true },
    query: { type: Object, required: true },
  },
  data: () => ({
    localQuery: defaultQueryValues,
    statuss: [
      { label: '全部', val: null },
      { label: '待推荐', val: 0 },
      { label: '已推荐', val: 1 },
      { label: '已驳回', val: 2 },
    ]
  }),
  computed: {
    getSearchQuery() {
      return lodash.reduce(this.localQuery, (query, value, key) => {
        if (value || value == 0) {
          query[key] = value;
        }

        return query;
      }, {});
    }
  },
  methods: {
    handleSelectedQuestion({ id }) {
      this.localQuery.question = id;
    },
    handleSelectedUser({ id }) {
      this.localQuery.user = id;
    }
  }
};
</script>
