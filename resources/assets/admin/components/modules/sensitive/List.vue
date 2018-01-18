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

      <!-- Foreach list. -->
      <module-sensitive-list-item
        v-for="sensitive in sensitives"
        :key="sensitive.id"
        :sensitive="sensitive"
        :handle-change="handleChange"
        :handle-delete="handleDelete"
      >
      </module-sensitive-list-item>

      <!-- Store. -->
      <module-sensitive-store :handle-append="handleAppend"></module-sensitive-store>
    </tbody>
  </table>
</template>

<script>
import Store from './Store';
import ListItem from './ListItem';
export default {
  name: 'module-sensitive-list',
  components: {
    [Store.name]: Store,
    [ListItem.name]: ListItem,
  },
  props: {
    sensitives: { type: Array, default: () => ([]) },
    handleAppend: { type: Function, required: true },
    handleChange: { type: Function, required: true },
    handleDelete: { type: Function, required: true },
  },
};
</script>
