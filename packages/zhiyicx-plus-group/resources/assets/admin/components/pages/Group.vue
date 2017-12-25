<template>
<div>

  <ui-panle title="检索条件">
    <!-- 回收站 -->
    <router-link to="/groups" class="btn btn-link btn-sm pull-right" slot="slot-panel-heading"  
    v-if="isTrash">
      <i class="glyphicon glyphicon-list"></i>圈子列表
    </router-link>

    <router-link  :to="{ path: '/groups', query: { type: 'trash' } }" class="btn btn-link btn-sm pull-right" slot="slot-panel-heading" v-else>
      <i class="glyphicon glyphicon-trash"></i>圈子回收站
    </router-link>

    <group-search></group-search>
  </ui-panle>

  <ui-panle title="圈子管理">
    <!-- 添加 -->
    <router-link to="/groups/add" class="btn btn-link btn-sm pull-right" slot="slot-panel-heading">
      <i class="glyphicon glyphicon-plus"></i>添加
    </router-link>
    <!-- 列表 -->
    <group-list :items="items"></group-list>
    
    <!-- 分页 -->
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
import components from '../modules/group';
import { admin } from '../../axios';
export default({
  components: components,
  data:()=>({
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
      this.getGroups({ ...$route.query });
    },
  },
  methods: {
    getGroups(query = {}) {
      admin.get(
        'groups',{
          validateStatus: status => status === 200,
          params: { ...query, limit: 15 }
      })
      .then(({ data = [], headers: { 'x-total': total } }) => {
        this.items = data;
        this.total = parseInt(total);
      })
    },
    buildLocalhost(offset) {
      const { query } = this.$route;

      return { path: '/groups', query: { ...query, offset  } };
    },
  },
  created() {
    this.getGroups(this.$route.query);
  }
});  
</script>