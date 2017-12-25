<template>
  <ui-panle title="查看圈子">

  	<!-- panle-header -->
    <router-link to="/groups" class="btn btn-link btn-sm pull-right" slot="slot-panel-heading">
      <i class="glyphicon glyphicon-menu-left"></i>返回
    </router-link>

    <!-- panle-content -->
    <div class="row">
      <div class="col-lg-4">
      	<img :src="group.avatar" class="img-circle" style="max-width:100px;">
		  <p>#ID： {{ group.id }}</p>
		  <p>名称： {{ group.name }}</p>
		  <p>分类： {{ group.hasOwnProperty('category') ? group.category.name : '' }}</p>
		  <p>类别： {{ group.mode | mode }} 		  	
		  	<span class="text-danger" v-show="group.mode=='paid'">{{ group.money | money }}</span>
		  </p>
		  <p>
		  	标签： <span class="label label-success" v-for="tag in group.tags" style="margin-left:2px;">{{ tag.name }}</span>
		  </p>
		  <p>简介： {{ group.summary }}</p>
		  <p>公告： {{ group.notice }}</p>
      </div>
      <div class="col-lg-8">
		  <p>圈主： {{ group.hasOwnProperty('group_founder') ? group.group_founder.name : '' }}</p>
		  <p>位置： {{ group.location }}</p>
		  <p>创建者： {{ group.hasOwnProperty('user') ? group.user.name : '' }}</p>
		  <p>帖子数： {{ group.posts_count }}</p>
		  <p>成员数： {{ group.users_count }}</p>
		  <p>创建时间： {{ group.created_at | localDate }}</p>
		  <p>发帖权限： {{ (group.hasOwnProperty('permissions') ? group.permissions : '') | permissions }}</p>
		  <p>是否推荐： {{ group.recommend ? '已推荐' : '未推荐' }}</p>
		  <p>是否启用： {{ group.audit === 3 ? '禁用' : '启用' }}</p>
		  <p>
	        <router-link tag="button" class="btn btn-default" :to="`/posts?group_id=${id}`">
	          帖子管理
	        </router-link>
	        <router-link tag="button" class="btn btn-default" :to="`/groups/${id}/members`">
	          成员管理
	        </router-link>
		  </p>
      </div>	
    </div>
  </ui-panle>
</template>
<script>
import { admin, api } from '../../axios';
export default({
  data:()=>({
    id:null,
    group:{},
  }),

  watch: {

  },

  computed: {
  },

  filters: {
    permissions(val) {
      let text = '所有人';
      let len = val.split(',').length;
      if (len === 2) {
      	text = '管理员和圈主';
      } else if (len == 1){
      	text = '仅圈主';
      }
      return text;
    },
    mode(val) {
      let text = '公开';
      if (val === 'private') {
      	text = '私密';
      } else if (val == 'paid'){
      	text = '收费';
      }
      return text;
    }
  },

  methods:{
    getGroup() {
      admin.get(`groups/${this.id}`,{
        validateStatus: status => status === 200,
      }).then(({ data = [] })=>{
        this.group = data;
      }).catch(error=>{

      });
    }
  },

  created() {
    this.id = this.$route.params.id;
    this.getGroup();
  }
});
</script>