<template>
 <div class="form-horizontal">
   <div class="form-group">
     <label class="control-label col-xs-2">评论者</label>
     <div class="col-xs-2">
     	<input type="text" class="form-control" v-model="search.user" placeholder="评论者名／评论者ID">
     </div>
     <div class="col-xs-3">
       <module-search-user :handleSelected="searchUserCall"></module-search-user>
     </div>
     <div class="col-xs-5 help-block">
     	按评论者搜索，名称支持模糊匹配
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
     	根据评论时间段搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2">关键词</label>
     <div class="col-xs-5">
        <input type="text" class="form-control" v-model="search.keyword" placeholder="评论内容关键词搜索">
     </div>
     <div class="col-xs-5 help-block">
     	按圈子分类进行搜索
     </div>
   </div>
   <div class="form-group">
     <label class="control-label col-xs-2"></label>
     <div class="col-xs-5">
        <router-link :to="{ path: '/comments', query: search }" class="btn btn-default">
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
import SearchUser from '../../modules/SearchUser';
export default({
  components: {
    'module-search-user': SearchUser,
  },
  data:()=>({
    categories: [],
    search: {
      keyword: null,
      user: null,
      start: null,
      end: null,
    }
  }),
  methods: {
    searchUserCall(id) {
      this.search.user = id;
    }
  },
  created() {
  }
});
</script>