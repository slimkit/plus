<template>
<div>
  <ui-panle title="检索条件"> 
    <comment-search></comment-search>
  </ui-panle>

  <ui-panle title="评论管理">	
    <comment-list :items="items"></comment-list>
    <ui-paginator class="pagination" :total="total" :offset="offset" :limit="15">
      <template slot-scope="pagination">
        <li :class="(pagination.disabled ? 'disabled': '') + (pagination.currend ? 'active' : '')">
          <span v-if="pagination.disabled || pagination.currend">{{ pagination.page }}</span>
          <router-link v-else :to="buildLocalhost(pagination.offset)">{{ pagination.page }}</router-link>
        </li>
      </template>
    </ui-paginator>
  </ui-panle>
</div>
</template>
<script>
import components from '../modules/comment';
import { admin } from '../../axios';

export default({
  components: components,
  data:()=>({
    total: 0,
    items: [],
  }),
  computed: {
    offset () {
      const { query: { offset = 0 } } = this.$route;

      return parseInt(offset);
    }
  },
  watch: {
    '$route': function ($route) {
      this.total = 0;
      this.getCategories({ ...$route.query });
    },
  },
  methods: {
    getCategories(query = {}) {
      admin.get(
        'comments',{
          validateStatus: status => status === 200,
          params: { ...query, limit: 15 }
      })
      .then(({ data = [], headers: { 'x-total': total } }) => {
        this.loading = false;
        this.items = data;
        this.total = parseInt(total);
      })
    },
    buildLocalhost(offset) {
      const { query } = this.$route;

      return { path: '/comments', query: { offset } };
    },
  },
  created() {
    this.getCategories(this.$route.query);
  }
});
</script>