<template>
<!-- 搜索 -->
<div class="panel panel-default">
  <div class="panel-body">
    <div class="form-horizontal">
      <div class="col-md-10 col-md-offset-1">
        <div class="form-group">
          <label class="control-label col-md-2">话题名称</label>
          <div class="col-md-5">
            <input type="text" class="form-control" v-model="localQuery.name" placeholder="输入话题名称进行搜索">
          </div>
          <div class="col-md-5">
            <span class="help-block">按话题名称进行搜索，支持模糊匹配</span>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">审核状态</label>
          <div class="col-md-5">
            <select class="form-control" v-model="localQuery.status">
              <option :value="status.val" v-for="status in statuss">{{ status.label }}</option>
            </select>
          </div>
          <div class="col-md-5">
            <span class="help-block">按话题状态进行搜索</span>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2"></label>
          <div class="col-md-5">
            <button v-if="searching" class="btn btn-primary" disabled="disabled">
              <ui-loading></ui-loading>
              搜索中...
            </button>
            <router-link v-else tag="a" :to="{ path: '/topics/applications', query: getSearchQuery }" class="btn btn-primary">
              搜索
            </router-link>
          </div>
          <div class="col-md-5"></div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  name: 'module-topic-application-search',
  data: () => ({
    statuss: [
      { label: '全部', val: '' },
      { label: '待审核', val: 0 },
      { label: '已审核', val: 1 },
      { label: '已驳回', val: 2 },
    ],
    localQuery: {
      name: '',
      status: '',
    },
  }),
  props: {
    searching: { type: Boolean, default: false },
    query: { type: Object, required: true },
  },
  computed: {
    getSearchQuery () {
      const { name, status } = this.localQuery;

      return { name, status };
    }
  },
  created () {
    // this.localQuery = { ...this.query };
  }
};
</script>