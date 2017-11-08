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
    <button type="submit" class="btn btn-default">搜索</button>

  </div>
</template>

<script>
export default {
  name: 'module-sensitive-search',
};
</script>
