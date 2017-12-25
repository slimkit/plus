<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      条件筛选
      <router-link v-if="isTrash" to="/questions" class="pull-right" replace>
        <span class="glyphicon glyphicon-list"></span>
        问题列表
      </router-link>
      <router-link v-else :to="{ path: '/questions', query: { trash: true } }" class="pull-right" replace>
        <span class="glyphicon glyphicon-trash"></span>
        回收站
      </router-link>
    </div>

    <div class="panel-body">
      <div class="form-horizontal">

        <!-- 问题 ID -->
        <div class="form-group">
          <label class="col-sm-2 control-label">ID</label>
          <div class="col-sm-6">
            <input type="number" class="form-control" placeholder="输入需要检索的问题 ID" min="1" v-model="localQuery.id">
          </div>
          <span class="col-sm-4 help-block">
            需要检索的问题ID，输入 ID 意味着直接过去对应数据。
          </span>
        </div>

        <!-- 问题标题 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">标题</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" placeholder="问题标题" v-model="localQuery.subject">
          </div>
          <span class="col-sm-4 help-block">
            需要检索的问题标题，支持模糊搜索关键词。
          </span>
        </div>

        <!-- 发布者 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">发布者</label>
          <div class="col-sm-2">
            <input type="number" class="form-control" placeholder="发布者" v-model="localQuery.user">
          </div>
          <div class="col-sm-4">
            <module-search-user :handleSelected="searchUserCall"></module-search-user>
          </div>
          <span class="col-sm-4 help-block">
            输入发布者用户 ID，如果不知道用户 ID，请选择输入框后面的搜索框搜索用户。
          </span>
        </div>

        <!-- 选择话题 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">话题</label>
          <div class="col-sm-2">
            <input class="form-control" type="number" name="topic" v-model="localQuery.topic" placeholder="话题 ID">
          </div>
          <div class="col-sm-4">
            <module-search-topic :handleSelected="searchTopicCall"></module-search-topic>
          </div>
          <span class="col-sm-4 help-block">
            输入话题 ID，如果不清楚话题 ID，可使用右侧输入框进行检索。
          </span>
        </div>

        <!-- 精选 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">精选</label>
          <div class="col-sm-6">
            <label class="radio-inline">
              <input type="radio" name="excellent" :value="undefined" v-model="localQuery.excellent"> 全部
            </label>
            <label class="radio-inline">
              <input type="radio" name="excellent" value="1" v-model="localQuery.excellent"> 精选
            </label>
            <label class="radio-inline">
              <input type="radio" name="excellent" value="0" v-model="localQuery.excellent"> 普通
            </label>
          </div>
          <span class="col-sm-4 help-block">
            筛选精选类别。
          </span>
        </div>

        <!-- 类型 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">类型</label>
          <div class="col-sm-6">

            <select class="form-control" name="type" v-model="localQuery.type">
              <option :value="undefined">全部</option>
              <option value="1">邀请悬赏</option>
              <option value="2">公开悬赏</option>
              <option value="3">普通问题</option>
            </select>

          </div>
          <span class="col-sm-4 help-block">选择问题类型</span>
        </div>

        <!-- 回答状态 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">回答状态</label>
          <div class="col-sm-6">
            <label class="radio-inline">
              <input type="radio" name="status" :value="undefined" v-model="localQuery.status"> 全部
            </label>
            <label class="radio-inline">
              <input type="radio" name="status" value="1" v-model="localQuery.status"> 有回答
            </label>
            <label class="radio-inline">
              <input type="radio" name="status" value="0" v-model="localQuery.status"> 无回答
            </label>
          </div>
          <span class="col-sm-4 help-block">
            选择问题回答状态
          </span>
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
            <router-link v-else tag="button" :to="{ path: '/questions', query: getSearchQuery }" class="btn btn-primary">
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
import SearchUser from '../SearchUser';
import SearchTopic from '../SearchTopic';
export default {
  components: {
    'module-search-user': SearchUser,
    'module-search-topic': SearchTopic
  },
  name: 'module-question-search',
  props: { query: Object, searching: Boolean },
  data: () => ({
    localQuery: {}
  }),
  computed: {
    isTrash() {
      const { trash } = this.query;

      return !! trash;
    },
    getSearchQuery() {
      const { id, subject, user, topic, excellent, type, status, start_date, end_date, trash } = this.localQuery;

      let query = lodash.reduce({ id, subject, user, topic, type, start_date, end_date, trash }, (reduce, value, key) => {
        if (value) {
          reduce[key] = value;
        }

        return reduce;
      }, {});

      if (excellent !== undefined) {
        query['excellent'] = excellent;
      }
      if (status !== undefined) {
        query['status'] = status;
      }

      return query;
    }
  },

  watch: {
    query(query) {
      this.initQuery(query);
    },
  },

  methods: {
    searchUserCall({ id: user }) {
      this.localQuery = { ...this.localQuery, user };
    },
    searchTopicCall({ id: topic }) {
      this.localQuery = { ...this.localQuery, topic };
    },
    initQuery(query = {}) {
      this.localQuery = { id: '', subject: '', user: '', topic: '', excellent: undefined, type: '', status: undefined, start_date: '', end_date: '', ...query };
    }
  },

  created () {
    this.initQuery(this.query);
  }
};
</script>
