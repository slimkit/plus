<template>
  <module-search-input
    placeholder="输入用户名搜索"
    :search="fetchSearchData"
    :handleSelected="handleSelected"
  >
    <template slot-scope="{ data: { id, name, avatar } }">
      <img v-if="avatar" :src="`${avatar}?s=40`" width="20px" height="20px">
      <span v-else class="glyphicon glyphicon-user"></span>
      {{ name }} &nbsp; (ID: {{ id }})
    </template>
  </module-search-input>
</template>

<script>
import { api } from '../../axios';
import SearchInput from './SearchInput';
export default {
  components: { 'module-search-input': SearchInput },
  name: 'module-search-user',
  props: {
    handleSelected: { type: Function, required: true }
  },
  methods: {
    fetchSearchData(keyword, commit, startSearch) {
      startSearch();
      api.get('/users', {
        validateStatus: status => status === 200,
        params: { name: keyword }
      }).then(({ data }) => commit(data)).catch(() => commit([]));
    }
  }
};
</script>
