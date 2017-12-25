<template>
  <div class="panel panel-default">
      
    <!-- Title -->
    <div class="panel-heading">
      筛选条件
      <router-link class="pull-right" to="/topic/add">
        <span class="glyphicon glyphicon-plus"></span>
        添加话题
      </router-link>
    </div>

    <!-- body -->
    <div class="panel-body">
      <div class="form-horizontal">

        <!-- 话题名称 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">话题</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" placeholder="输入需要检索的话题" v-model="localQuery.name">
          </div>
          <span class="col-sm-4 help-block">
            输入需要检索的话题关键词。
          </span>
        </div>

        <!-- 搜索按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button v-if="searching" class="btn btn-primary" disabled="disabled">
              <ui-loading></ui-loading>
              搜索中...
            </button>
            <router-link v-else tag="a" :to="{ path: '/topics', query: getSearchQuery }" class="btn btn-primary">
              搜索
            </router-link>
          </div>
        </div>

      </div>
    </div>

  </div>
</template>

<script>
const defaultQueryValues = { name: '' };
export default {
  name: 'module-topic-search',
  props: {
    searching: { type: Boolean, default: false },
    query: { type: Object, required: true },
  },
  data: () => ({
    localQuery: defaultQueryValues,
  }),
  computed: {
    getSearchQuery () {
      const { name } = this.localQuery;

      return { name };
    }
  },
  created () {
    this.localQuery = { ...defaultQueryValues, ...this.query };
  }
};
</script>
