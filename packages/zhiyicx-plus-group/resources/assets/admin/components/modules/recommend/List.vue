<template>
<ui-table :loading="loading" :colspanNum="8">
    <!-- thead -->
    <tr slot="table-thead-tr">
      <th>#ID</th>
      <th>名称</th>
      <th>圈主</th>
      <th>推荐人</th>
      <th>所属分类</th>
      <th>权重</th>
      <th>推荐时间</th>
      <th>操作</th>
    </tr>
    <!-- tbody -->
    <tr slot="table-tbody-tr" v-for="item in items">
      <td>{{ item.id }}</td>
      <td>{{ item.group.name }}</td>
      <td>{{ item.group.founder.user.name }}</td>
      <td>{{ item.referrer.name }}</td>
      <td>{{ item.group.category.name }}</td>
      <td>
          <input type="number" :value="item.sort_by" class="form-control" @change="handleSort(item.id, $event.target.value)">
      </td>
      <td>{{ item.created_at | localDate }}</td>
      <td>
          <button class="btn btn-danger btn-sm" @click="handleRemove(item.id)">移除推荐</button>
      </td>
    </tr>
</ui-table>
</template>
<script>
import { admin } from '../../../axios';

export default{
    props: {
      items: {
        type: Array,
      }
    },

    data:()=>({
        loading: true,
    }),
    watch: {
      'items'(a, b){
        this.loading = false;
      }
    },

    methods:{
      handleRemove(id) {
        if (confirm('是否要进行该操作？')) {
          admin.delete(`recommends/${id}`,{
              validateStatus: status => status === 204,
          }).then(({ data }) => {
              this.items.forEach((item, index) => {
                if (item.id == id) return this.items.splice(index, 1);
              });
              this.$store.dispatch('alert-open', { type: 'success', message: { message: '操作成功' } });
          }).catch(({ data }) => {
              this.$store.dispatch('alert-open', { type: 'danger', message: data });
          });
        }
      },
      handleSort(id, val) {
          admin.patch(`recommends/${id}/sort`,{ sort: val },{
              validateStatus: status => status === 201,
          }).then(({ data: { message } }) => {
              this.items.forEach(function(item){
                if (parseInt(item.id) === parseInt(id)) item.sort_by = val;
              });
              this.items.sort((a, b)=>{
                return b.sort_by - a.sort_by;
              });
              this.$store.dispatch('alert-open', { type: 'success', message: { message: '操作成功' } });
          }).catch(({ response: { data } }) => {
              this.$store.dispatch('alert-open', { type: 'danger', message: data });
          });
      }
    }
}
</script>