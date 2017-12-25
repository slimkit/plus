<template>
  <module-search-input
    placeholder="输入话题搜索"
    :search="fetchSearchData"
    :handleSelected="handleSelected"
  >
    <template slot-scope="{ data: { id, name, avatar } }">
      <img v-if="avatar" :src="`${avatar}?s=40`" width="20px" height="20px">
      {{ name }} &nbsp; (ID: {{ id }})
    </template>
  </module-search-input>
</template>

<script>
import { api } from '../../axios';
import SearchInput from './SearchInput';
export default {
  components: { 'module-search-input': SearchInput },
  name: 'module-search-topic',
  props: {
    handleSelected: { type: Function, required: true }
  },
  methods: {
    fetchSearchData(keyword, commit, startSearch) {
      startSearch();
      api.get('/question-topics', {
        validateStatus: status => status === 200,
        params: { name: keyword }
      }).then(({ data }) => commit(data)).catch(() => commit([]));
    }
  }
};
</script>
