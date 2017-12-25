<template>
  <div class="panel panel-default">

    <div class="panel-heading">
      条件筛选

      <router-link v-if="isTrash" to="/answers" class="pull-right" replace>
        <span class="glyphicon glyphicon-list"></span>
        回答列表
      </router-link>
      <router-link v-else :to="{ path: '/answers', query: { trash: true } }" class="pull-right" replace>
        <span class="glyphicon glyphicon-trash"></span>
        回收站
      </router-link>

    </div>

    <div class="panel-body">
        
      <div class="form-horizontal">

        <!-- 回答 ID -->
        <div class="form-group">
          <label class="col-sm-2 control-label">回答 ID</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" placeholder="输入需要检索的回答ID" min="1" v-model="localQuery.id">
          </div>
          <span class="col-sm-4 help-block">
            需要检索的回答ID，输入 ID 意味着直接过去对应数据。
          </span>
        </div>

        <!-- 问题 ID -->
        <div class="form-group">
          <label class="col-sm-2 control-label">问题 ID</label>
          <div class="col-sm-2">
            <input type="number" class="form-control" placeholder="问题 ID" min="1" v-model="localQuery.question">
          </div>
          <div class="col-sm-4">
            <module-search-question :handle-selected="handleSelectedQuestion"></module-search-question>
          </div>
          <span class="col-sm-4 help-block">
            选择所属问题，如果不知道 ID，可以使用搜索栏检索。
          </span>
        </div>

        <!-- 回答者 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">回答者</label>
          <div class="col-sm-2">
            <input type="number" class="form-control" placeholder="回答者" min="1" v-model="localQuery.user">
          </div>
          <div class="col-sm-4">
            <module-search-user :handle-selected="handleSelectedUser"></module-search-user>
          </div>
          <span class="col-sm-4 help-block">输入回答者ID，不清楚 ID，可使用后面输入框检索。</span>
        </div>

        <!-- 类型 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">类型</label>
          <div class="col-sm-6">
            <select class="form-control" v-model="localQuery.type">
              <option :value="undefined">全部</option>
              <option value="1">邀请悬赏</option>
              <option value="2">被采纳</option>
              <option value="3">普通问题</option>
            </select>
          </div>
          <span class="col-sm-4 help-block">选择回答类型。</span>
        </div>

        <!-- 打赏 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">打赏</label>
          <div class="col-sm-6">
            <select class="form-control" v-model="localQuery.reward">
              <option :value="undefined">全部</option>
              <option value="1">有打赏</option>
              <option value="0">无打赏</option>
            </select>
          </div>
        </div>

        <!-- 发布时间 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">发布时间</label>
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
            <router-link v-else tag="a" :to="{ path: '/answers', query: getSearchQuery }" class="btn btn-primary">
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

const defaultQuery = { id: '', question: '', user: '', type: undefined, reward: undefined, start_date: '', end_date: '' };

export default {
  components: {
    'module-search-user': SearchUser,
    'module-search-question': SearchQuestion
  },
  name: 'module-answer-search',
  props: {
    query: { type: Object, required: true },
    searching: Boolean
  },
  data: () => ({
    localQuery: { ...defaultQuery }
  }),
  computed: {
    isTrash() {
      const { trash } = this.query;

      return !! trash;
    },
    getSearchQuery() {
      const query = lodash.reduce(this.localQuery, (reduce, value, key) => {
        if (value) {
          reduce[key] = value;
        }

        return reduce;
      }, {});

      const { type, reward } = this.localQuery;
      const query2 = lodash.reduce({ type, reward }, (reduce, value, key) => {
        if (value !== undefined) {
          reduce[key] = value;
        }

        return reduce;
      }, {});

      return { ...query, ...query2 };
    }
  },
  methods: {
    handleSelectedQuestion({ id }) {
      this.localQuery.question = id;
    },

    handleSelectedUser({ id }) {
      this.localQuery.user = id;
    }
  },
  watch: {
    query(query) {
      this.localQuery = { ...defaultQuery, ...query };
    }
  },
  created() {
    this.localQuery = { ...defaultQuery, ...this.query };
  }
};
</script>
