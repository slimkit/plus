<template>
    <div style="padding: 15px;">
        <div v-show="errorMessage" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ errorMessage }}
        </div>
        <div v-show="successMessage" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" @click.prevent="offAlert">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ successMessage }}
        </div> 
        <div class="panel panel-default">
          <div class="panel-heading">
            <router-link to="/setting/filter-word-categories/add" class="btn btn-primary btn-sm">添加</router-link>
          </div>
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>名称</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <table-loading :loadding="loadding" :colspanNum="3"></table-loading>
                    <tr v-for="category in categories">
                      <td><input type="checkbox"></td>
                      <td>{{ category.name }}</td>
                      <td>
                        <router-link :to="`/setting/filter-word-categories/${category.id}`" class="btn btn-primary btn-sm">编辑</router-link>
                        <button class="btn btn-danger btn-sm" @click.prevent="deleteCategory(category.id)">删除</button>
                      </td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
    </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import plusMessageBundle from 'plus-message-bundle';
import tableLoading from '../common/TableLoading';
const FilterWordCategory = {
    components:{
      tableLoading,
    },
    data: () => ({
      loadding: true,
      categories: {},
      errorMessage: '',
      successMessage: '',
    }),
    methods: {
      getCategories () {
        request.get(
          createRequestURI('filter-word-categories'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;
          this.categories = response.data;
        }).catch(({ response: { data = {} } = {} }) => {
          this.loadding = false;
          let Message = new plusMessageBundle(data);
          this.errorMessage = Message.getMessage();
        });
      },
      deleteCategory (id) {
        let  bool = confirm('是否确认删除？');
        if (bool) {
          request.delete(
            createRequestURI('filter-word-categories/' + id),
            { validateStatus: status => status === 204 }
          ).then(({ data: { message: [ message ] = [] } }) => {
            this.successMessage = '删除成功';
          }).catch(({ response: { data: {message = []} } = {} }) => {
            let Message = new plusMessageBundle(data);
            this.errorMessage = Message.getMessage();
          });
        }
      },
      offAlert () {
        this.errorMessage = this.successMessage = '';
      },
    },

    created () {
      this.getCategories();
    },
};
export default FilterWordCategory;
</script>
