<template>
  <div class="form-inline">
    
    <!-- word. -->
    <div class="form-group">
      <label class="sr-only">敏感词</label>
      <input type="text" class="form-control" placeholder="输入敏感词">
    </div>

    <!-- type。 -->
    <div class="form-group">
      <label class="sr-only">类型</label>
      <select class="form-control">
        <option :value="null">全部</option>
        <option value="replace">替换</option>
        <option value="warning">提示</option>
      </select>
    </div>

    <!-- button. -->
    <button v-if="searching" disabled type="button" class="btn btn-default">
      <ui-loading></ui-loading>
      搜索中...
    </button>
    <button  v-else type="submit" class="btn btn-default">搜索</button>

  </div>
</template>

<script>
export default {
  name: 'module-sensitive-search',
  props: {
    searching: { type: Boolean, default: false },
  },
};
</script>
