<template>
<ui-table :loading="loading" :colspanNum="7">
    
    <!-- thead -->
    <tr slot="table-thead-tr">
    	<th>#ID</th>
    	<th>评论者</th>
    	<th>帖子发布者</th>
    	<th>被回复者</th>
    	<th>评论内容</th>
    	<th>评论时间</th>
    	<th>操作</th>
    </tr>

    <!-- tbody -->
    <tr slot="table-tbody-tr" v-for="item in items">
      <td>{{ item.id }}</td>
      <td>{{ item.user.name }}</td>
      <td>{{ item.target.name }}</td>
      <td>{{ item.reply ? item.reply.name : '无' }}</td>
      <td>{{ item.body }}</td>
      <td>{{ item.created_at | localDate }}</td>
      <td>
        <button class="btn btn-danger btn-sm" @click="handleDelete(item.id)">删除</button>
      </td>
    </tr>

</ui-table>
</template>
<script>
import { admin } from '../../../axios';

export default({

  props: {
    items: {
      type: Array,
    }
  },
  
  data:()=>({
    loading: true,
  }),
  
  watch: {
    items() {
      this.loading = false;
    }
  },

  filters: {  

  },

  methods: {
    handleDelete(id) {
      if (confirm('确定要删除么?')) {
          admin.delete(`comments/${id}`, {
            validateStatus: status => status === 204,
          }).then(response=>{
            this.items.forEach((item, index)=>{
               if(item.id == id)  this.items.splice(index, 1);
            });
            this.$store.dispatch('alert-open', { type: 'success', message: { message: '删除成功' } });
          }).catch(({ response: { data } })=> {
            this.$store.dispatch('alert-open', { type: 'danger', message: data });
          })
      }
    }
  },

  created() {
  }
});
</script>