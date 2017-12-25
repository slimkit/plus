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
     	<input type="text" class="form-control" v-model="search.user" placeholder="圈主名称">
     </div>
     <div class="col-xs-5 help-block">
     	按圈主名进行搜索，支持模糊匹配
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">时间段</label>
     <div class="col-xs-5">
          <div class="input-group">
              <input type="date" class="form-control" v-model="search.start">
              <div class="input-group-addon">-</div>
              <input type="date" class="form-control" v-model="search.end">
          </div>
     </div>
     <div class="col-xs-5 help-block">
      根据时间段搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">圈子分类</label>
     <div class="col-xs-5">
     	<select class="form-control" v-model="search.category">
     		<option value="">全部</option>
     		<option :value="cate.id" v-for="cate in categories">{{ cate.name }}</option>
     	</select>
     </div>
     <div class="col-xs-5 help-block">
     	按圈子分类进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2"></label>
     <div class="col-xs-5">
        <router-link :to="{ path: '/recommends', query: search }" class="btn btn-default">
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
      category: '',
      name:'',
      user: '',
      start: '',
      end: '',
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