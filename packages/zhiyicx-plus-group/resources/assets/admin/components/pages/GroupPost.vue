<template>
  <div>
    <ui-panle title="检索条件">
      <!-- 回收站 -->
      <router-link to="/posts" class="btn btn-link btn-sm pull-right" slot="slot-panel-heading"  
      v-if="isTrash">
        <i class="glyphicon glyphicon-list"></i>圈子列表
      </router-link>

      <router-link  :to="{ path: '/posts', query: { type: 'trash' } }" class="btn btn-link btn-sm pull-right" slot="slot-panel-heading" v-else>
        <i class="glyphicon glyphicon-trash"></i>圈子回收站
      </router-link>

      <post-search></post-search>
    </ui-panle>
    <ui-panle title="圈子帖子管理">
      <!-- panle-header -->
      <button class="btn btn-link pull-right" slot="slot-panel-heading" @click="$router.go(-1)">
        <i class="glyphicon glyphicon-menu-left"></i>返回
      </button>
      <!-- post-list -->
      <post-list :items="items"></post-list>

      <!-- post-paginate -->
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
import components from '../modules/post';
import { admin } from '../../axios';
export default({
  components: components,
  data:() => ({
  	groupId: null,
    items: [],
    total: 0,
  }),

  computed: {
    offset () {
      const { query: { offset = 0 } } = this.$route;

      return parseInt(offset);
    },
    isTrash() {
      const { type } = this.$route.query;

      return !! type;
    },
  },

  watch: {
    '$route'($route) {
      this.total = 0;
      this.getPosts({ ...$route.query });
    },
  },

  methods: {
    getPosts(query = {}) {
      admin.get(
        `posts`,{
          validateStatus: status => status === 200,
          params: { ...query, limit: 15 }
      }).then(({ data = [], headers: { 'x-total': total } }) => {
        this.loading = false;
        this.items = data;
        this.total = parseInt(total);
      })
    },

    buildLocalhost(offset) {
      const { query } = this.$route;

      return { path: `posts`, query: { ...query, offset  } };
    },

  },

  created() {
    this.groupId = parseInt(this.$route.query.group_id);
    this.getPosts(this.$route.query);
  }
});
</script>