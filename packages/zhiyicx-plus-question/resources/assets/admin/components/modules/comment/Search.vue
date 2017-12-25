<template>
  <div class="panel panel-default">

    <!-- Title -->
    <div class="panel-heading">筛选条件</div>

    <!-- body -->
    <div class="panel-body">
      <div class="form-horizontal">
          
        <!-- Comment ID -->
        <div class="form-group">
            
          <label class="col-sm-2 control-label">ID</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" placeholder="输入需要检索的评论 ID" min="1" v-model="localQuery.id">
          </div>
          <span class="col-sm-4 help-block">
            输入需要搜索的评论 ID。
          </span>

        </div>

        <!-- Source type -->
        <div class="form-group">
            
          <label class="col-sm-2 control-label">资源类型</label>
          <div class="col-sm-6">
            <select class="form-control" v-model="localQuery.type">
              <option :value="undefined">全部</option>
              <option value="question">问题</option>
              <option value="answer">回答</option>
            </select>
          </div>
          <span class="col-sm-4 help-block">
            选择评论所属的资源类型。
          </span>

        </div>

        <!-- Question -->
        <div v-show="localQuery.type === 'question'" class="form-group">
          
          <label class="col-sm-2 control-label">问题ID</label>
          <div class="col-sm-2">
            <input type="number" class="form-control" min="1" placeholder="问题ID" v-model="localQuery.question">
          </div>
          <div class="col-sm-4">
            <module-search-question :handle-selected="handleSelectedQuestion"></module-search-question>
          </div>
          <span class="col-sm-4 help-block">
            检索问题下的评论，如果不知道问题ID，可输入关键词检索。
          </span>

        </div>

        <!-- User -->
        <div class="form-group">
          
          <label class="col-sm-2 control-label">评论者</label>
          <div class="col-sm-2">
            <input type="number" class="form-control" min="1" placeholder="评论者" v-model="localQuery.user">
          </div>
          <div class="col-sm-4">
            <module-search-user :handle-selected="handleSelectedUser"></module-search-user>
          </div>
          <span class="col-sm-4 help-block">
            输入评论者ID，如果不知道ID请使用输入框检索。
          </span>

        </div>

        <!-- Date -->
        <div class="form-group">
          
          <label class="col-sm-2 control-label">评论时间</label>
          <div class="col-sm-10">
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
            <router-link v-else tag="button" :to="{ path: '/comments', query: getSearchQuery }" class="btn btn-primary">
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
const defaultQuery = { id: '', type: undefined, question: '', user: '', start_date: '', end_date: '' };
export default {
  components: {
    'module-search-question': SearchQuestion,
    'module-search-user': SearchUser
  },
  name: 'module-comment-search',
  props: {
    query: { type: Object, required: true },
    searching: { type: Boolean, default: false }
  },
  data: () => ({
    localQuery: { ...defaultQuery }
  }),
  computed: {
    getSearchQuery() {
      return lodash.reduce(this.localQuery, (reduce, value, key) => {
        if (value) {
          reduce[key] = value;
        }

        return reduce;
      }, {});
    }
  },
  watch: {
    query(query) {
      this.localQuery = { ...defaultQuery, ...query };
    }
  },
  methods: {
    handleSelectedQuestion(question) {
      this.localQuery.question = question.id;
    },
    handleSelectedUser(user) {
      this.localQuery.user = user.id;
    }
  },
  created() {
    this.localQuery = { ...defaultQuery, ...this.query };
  }
};
</script>
