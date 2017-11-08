<template>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>敏感词</th>
        <th>类型</th>
        <th>替换词</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <module-sensitive-store></module-sensitive-store>
    </tbody>
  </table>
</template>

<script>
import Store from './Store';
export default {
  name: 'module-sensitive-list',
  components: {
    [Store.name]: Store,
  },
};
</script>
