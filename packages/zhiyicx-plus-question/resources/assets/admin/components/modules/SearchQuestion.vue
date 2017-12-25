<template>
  <module-search-input
    placeholder="输入问题标题"
    :search="fetchQuestionData"
    :handleSelected="handleSelected"
  >
    <template slot-scope="{ data: { id, subject } }">
      {{ id }}：{{ subject }}
    </template>
  </module-search-input>
</template>

<script>
import { admin } from '../../axios';
import SearchInput from './SearchInput';
export default {
  components: { 'module-search-input': SearchInput },
  name: 'module-search-question',
  props: { handleSelected: { type: Function, required: true } },
  methods: {
    fetchQuestionData(keyword, commit, startSearch) {
      startSearch();
      admin.get('/questions', {
        params: { subject: keyword, limit: 10 },
        validateStatus: status => status === 200
      }).then(({ data }) => commit(data)).catch(() => commit([]));
    }
  }
}
</script>
