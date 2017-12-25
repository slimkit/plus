<template>
 <div class="form-horizontal">
   <div class="form-group">
     <label class="control-label col-xs-2">标题</label>
     <div class="col-xs-5">
     	<input type="text" class="form-control" v-model="search.title" placeholder="帖子标题">
     </div>
     <div class="col-xs-5 help-block">
     	按帖子标题进行搜索，支持模糊匹配
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">发帖人</label>
     <div class="col-xs-5">
     	<input type="text" class="form-control" v-model="search.user" placeholder="发帖人名">
     </div>
     <div class="col-xs-5 help-block">
     	按发帖人名称进行搜索，支持名称支持模糊匹配
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">是否置顶</label>
     <div class="col-xs-5">
		<select v-model="search.pinned" class="form-control">
		  <option value="">全部</option>
		  <option value="1">已审核</option>
		  <option value="0">待审核</option>
		  <option value="2">已驳回</option>
		</select>
     </div>
     <div class="col-xs-5 help-block">
     	根据置顶状态进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">所属圈子</label>
     <div class="col-xs-5">
        <select v-model="search.group_id" class="form-control">
          <option value="">全部</option>
          <option :value="group.id" v-for="group in groups">{{ group.name }}</option>
        </select>
     </div>
     <div class="col-xs-5 help-block">
      根据置圈子类型进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2"></label>
     <div class="col-xs-5">
        <router-link :to="{ path: path, query: search }" class="btn btn-default">
          搜索
        </router-link>
     </div>
     <div class="col-xs-5 help-block">
     </div>
   </div>
 </div>
</template>
<script>
import { admin } from '../../../axios';
export default({
  data:()=>({
  	path: null,
    groups: [],
    search: {
      title: '',
      user:'',
      pinned:'',
      group_id: '',
    }
  }),

  methods: {
    getGroups() {
      admin.get('groups?type=all', {
        validateStatus: status => status === 200,
      }).then(({ data })=>{
          this.groups = data;
      }).catch(({data:{ message }})=>{
        window.alert(message);
      });
    }
  },

  created() {
    this.path = location.hash.substring(1);
    this.getGroups();
    let groupId = this.$route.query.group_id;
    this.search.group_id = groupId ? groupId : '';
  }
});
</script>