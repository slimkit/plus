<template>
 <div class="form-horizontal">
   <div class="form-group">
     <label class="control-label col-xs-2">用户名</label>
     <div class="col-xs-5">
     	<input type="text" class="form-control" v-model="search.user" placeholder="用户名">
     </div>
     <div class="col-xs-5 help-block">
     	按用户名进行搜索，支持模糊匹配
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">状态</label>
     <div class="col-xs-5">
		<select v-model="search.audit" class="form-control">
		  <option value="">全部</option>
      <option value="1">已通过</option>
		  <option value="0">待通过</option>
		  <option value="2">未通过</option>
		</select>
     </div>
     <div class="col-xs-5 help-block">
     	根据成员状态进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">成员职位</label>
     <div class="col-xs-5">
    <select v-model="search.role" class="form-control">
      <option value="">全部</option>
      <option value="founder">圈主</option>
      <option value="administrator">管理员</option>
      <option value="member">普通成员</option>
    </select>
     </div>
     <div class="col-xs-5 help-block">
      根据成员职位进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">拉黑</label>
     <div class="col-xs-5">
    <select v-model="search.disable" class="form-control">
      <option value="">全部</option>
      <option value="1">已拉黑</option>
      <option value="0">未拉黑</option>
    </select>
     </div>
     <div class="col-xs-5 help-block">
      根据成员拉黑状态进行搜索
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
    search: {
      user: '',
      audit: '',
      role: '',
      disable: '',
    }
  }),

  methods: {
  },

  created() {
    this.path = location.hash.substring(1);
    console.log(this.path);
  }
});
</script>