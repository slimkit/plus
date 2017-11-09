<template>
  <div class="form-inline">
    
    <!-- word. -->
    <div class="form-group">
      <label class="sr-only">敏感词</label>
      <input type="text" class="form-control" placeholder="输入敏感词" v-model="query.word">
    </div>

    <!-- type。 -->
    <div class="form-group">
      <label class="sr-only">类型</label>
      <select class="form-control" v-model="query.type">
        <option :value="null" selected>全部</option>
        <option value="replace">替换</option>
        <option value="warning">提示</option>
      </select>
    </div>

    <!-- button. -->
    <button v-if="searching" disabled type="button" class="btn btn-default">
      <ui-loading></ui-loading>
      搜索中...
    </button>
    <router-link v-else class="btn btn-default" type="button" tag="button" :to="to">搜索</router-link>

  </div>
</template>

<script>
const defaultQuery = { word: null, type: null };
export default {
  name: 'module-sensitive-search',
  props: {
    searching: { type: Boolean, default: false },
  },
  data: () => ({
    query: defaultQuery,
  }),
  computed: {
    to () {
      const { word, type } = this.query;
      let query = {};
      if (word) {
        query.word = word;
      }

      if (type === 'replace' || type === 'warning') {
        query.type = type;
      }

      return { query, path: '/setting/sensitives' };
    }
  },
  created () {
    const { query } = this.$route;
    this.query = { ...defaultQuery, ...query };
  }
};
</script>
