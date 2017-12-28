<template>
<div>
	<ui-panle title="检索条件">
		<member-search></member-search>
	</ui-panle>

	<ui-panle title="圈子成员管理">

  	<!-- panle-header -->
    <button class="btn btn-link pull-right" slot="slot-panel-heading" @click="$router.go(-1)">
    	<i class="glyphicon glyphicon-menu-left"></i>返回
    </button>

	<!-- post-list -->
	<member-list :items="items"></member-list>
	
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
import components from '../modules/member';
export default({
  	components: components,
	data:()=>({
		groupId: null,
		items: [],
		total:0,
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
	      this.getMembers({ ...$route.query });
	    },
    },
	methods: {
	    getMembers(query = {}) {
	      admin.get(`groups/${this.groupId}/members`,{
	          validateStatus: status => status === 200,
	          params: { ...query, limit: 15 }
	      })
	      .then(({ data = [], headers: { 'x-total': total } }) => {
	        this.items = data;
	        this.total = parseInt(total);
	      });
	    },
	    buildLocalhost(offset) {
	      const { query } = this.$route;

	      return { path: `groups/${this.groupId}/members`, query: { ...query, offset } };
	    },
	},

	created() {
		this.groupId = this.$route.params.id;
    	this.getMembers(this.$route.query);
	}
})
</script>