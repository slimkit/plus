<template>
 <div class="form-horizontal">
   <div class="form-group">
     <label class="control-label col-xs-2">圈子名称</label>
     <div class="col-xs-5">
     	<input type="text" class="form-control" v-model="search.name" placeholder="圈子名称">
     </div>
     <div class="col-xs-5 help-block">
     	按圈名进行搜索，支持模糊匹配
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">圈主名称</label>
     <div class="col-xs-5">
     	<input type="text" class="form-control" v-model="search.user_name" placeholder="圈主名称">
     </div>
     <div class="col-xs-5 help-block">
     	按圈主名进行搜索，支持模糊匹配
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">圈子分类</label>
     <div class="col-xs-5">
     	<select class="form-control" v-model="search.category_id">
     		<option value="">全部</option>
     		<option :value="cate.id" v-for="cate in categories">{{ cate.name }}</option>
     	</select>
     </div>
     <div class="col-xs-5 help-block">
     	按圈子分类进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">审核状态</label>
     <div class="col-xs-5">
     	<select class="form-control" v-model="search.audit">
        <option value="">全部</option>
     		<option value="0">待通过</option>
        <option value="1">已通过</option>
        <option value="2">未通过</option>
        <option value="3">已关闭</option>
     	</select>
     </div>
     <div class="col-xs-5 help-block">
     	按圈子审核状态进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">圈子类型</label>
     <div class="col-xs-5">
     	<select class="form-control" v-model="search.mode">
     		<option value="">全部</option>
        <option value="public">公开</option>
        <option value="paid">收费</option>
        <option value="private">私密</option>
     	</select>
     </div>
     <div class="col-xs-5 help-block">
     	按圈子类别进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">是否推荐</label>
     <div class="col-xs-5">
     	<select class="form-control" v-model="search.pinned">
        <option value="">全部</option>
        <option value="1">是</option>
     		<option value="0">否</option>
     	</select>
     </div>
     <div class="col-xs-5 help-block">
     	按圈子置顶状态进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2"></label>
     <div class="col-xs-5">
        <router-link :to="{ path: '/groups', query: search }" class="btn btn-default">
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
    categories: [],
    search: {
      category_id: '',
      name:'',
      user_name: '',
      audit: '',
      mode: '',
      pinned: '',
    }
  }),
  methods: {
    getCategories() {
      admin.get(
        'categories?type=all',{
          validateStatus: status => status === 200,
          params: {},
      })
      .then(({ data = [] }) => {
        this.categories = data;
      })
    },
  },
  created() {
    this.getCategories();
  }
});
</script>