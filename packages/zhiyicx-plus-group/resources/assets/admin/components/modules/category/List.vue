<template>
<ui-table :loading="loading" :colspanNum="4">
    <!-- thead -->
    <tr slot="table-thead-tr">
      <th>#ID</th>
      <th>名称</th>
      <th>排序</th>
      <th>状态</th>
      <th>操作</th>
    </tr>
    <!-- tbody -->
    <tr slot="table-tbody-tr" v-for="item in items">
      <td>{{ item.id }}</td>
      <td>
        <input type="text" class="form-control" :value="item.name" @change="handleChange(item.id, $event.target.value, 'name')">
      </td>
      <td>
        <input type="number" class="form-control" :value="item.sort_by" @change="handleChange(item.id, $event.target.value, 'sort_by')">
      </td>
      <td>
        <select class="form-control" :value="item.status" @change="handleChange(item.id, $event.target.value, 'status')">
          <option value="0">启用</option>
          <option value="1">禁用</option>
        </select>
      </td>
      <td>
        <button class="btn btn-danger btn-sm" @click="handleDelete(item.id)">删除</button>
      </td>
    </tr>
    <!-- other -->
    <tr slot="table-tbody-other">
      <td>分类添加</td>
      <td>
          <input type="text" class="form-control" placeholder="分类名称" v-model="category.name"> 
      </td>
      <td>
        <input type="number" class="form-control" placeholder="权重排序" v-model="category.sort_by"> 
      </td>
      <td>
        <select class="form-control" v-model="category.status">
          <option value="0">启用</option>
          <option value="1">禁用</option>
        </select>
      </td>
      <td>
        <button class="btn btn-primary btn-sm" @click="handleCreate">添加</button>
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
    category: {
      name: null,
      sort_by: 1000,
      status: 0,
    },
    loading: true,
  }),
  watch: {
    'items'(a, b){
      this.loading = false;
    }
  },
  methods: {
    /**
     * 处理删除
     * @param  id
     * @return void
     */
    handleDelete(id) {
      if (confirm('确定要删除嘛？')) {
        admin.delete(`categories/${id}`)
        .then(response => {
          let _this = this;
          this.items.forEach(function(item, index){
            if (parseInt(item.id) === parseInt(id)) {
              _this.items.splice(index, 1);
            }
          });
          this.$store.dispatch('alert-open', { type: 'success', message: { message: '删除成功' } });
        })
        .catch(({ response: { data } })=> {
          this.$store.dispatch('alert-open', { type: 'danger', message: data });
        })
      }
    },
    /**
     * 处理修改
     * @param  id
     * @param  val
     * @return void
     */
    handleChange(id, val, type) {
      admin.patch(
        `categories/${id}`, { value: val, type: type },
        { validateStatus: status => status === 204 })
      .then(response => {
        if (! isNaN(val) && type == 'sort_by') {
          this.items.forEach(function(item){
            if (parseInt(item.id) === parseInt(id)) {
              item.sort_by = val;
            }
          });
          this.items.sort((a, b)=>{
            return b.sort_by - a.sort_by;
          });
        }
        this.$store.dispatch('alert-open', { type: 'success', message: { message: '修改成功'} });
      })
      .catch(({ response: { data } })=> {
          this.$store.dispatch('alert-open', { type: 'danger', message: data });
      })
    },
    /**
     * 创建分类
     * @return void
     */
    handleCreate() {
      admin.post(
        `categories`, { ...this.category },
        { validateStatus: status => status === 201 })
      .then(({ data }) => {
        this.items.push(data.category);
        this.$store.dispatch('alert-open', { type: 'success', message: data });
      })
      .catch(({ response: { data } })=> {
       this.$store.dispatch('alert-open', { type: 'danger', message: data });
      })
    }

  },
  created() {
  }
});
</script>