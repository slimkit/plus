<template>
<div>
	<!-- 检索条件 -->
    <ui-panle title="检索条件">
      <recommend-search></recommend-search>
	  </ui-panle>

	<!-- 推荐列表 -->
    <ui-panle title="推荐列表">
		<!-- panle-header -->
		<button class="btn btn-link pull-right" slot="slot-panel-heading" @click="$router.go(-1)">
		<i class="glyphicon glyphicon-menu-left"></i>返回
		</button>

		<!-- 列表 -->
		<recommend-list :items="items"></recommend-list>

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
import { admin } from '../../axios';
import components from '../modules/recommend';

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
    }
  },
  watch: {
    '$route'($route) {
      this.total = 0;
      this.getRecommends({ ...$route.query });
    },
  },
  methods: {
    getRecommends(query = {}) {
      admin.get(
        'recommends',{
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

      return { path: '/recommends', query: { ...query, offset  } };
    },
  },
  created() {
	console.log(1111);
    this.getRecommends(this.$route.query);
  }
});  
</script>