<template>
    <div class="container-fluid" style="margin-top:10px;">
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
            过滤词语类型列表
              <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/setting/filter-word-types/add" role="button">
                  添加
              </router-link>
          </div>
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><input type="checkbox" @click="selectAll"></th>
                        <th>类型</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <table-loading :loadding="loadding" :colspan-num="4"></table-loading>
                    <tr v-for="type in types">
                        <td><input type="checkbox" :value="type.id" :checked="checked"></td>
                        <td>{{ type.name }}</td>
                        <td>
                          <a class="label" 
                             :class="type.status ? 'label-success' : 'label-danger'" 
                             href="javascript:;" @click="changeStatus(type.id)">{{ type.status ? '开启' : '关闭' }}</a>
                        </td>
                        <td>
                          <router-link tag="a" class="btn btn-primary btn-sm" :to="`/setting/filter-word-types/${type.id}`" role="button">
                              编辑
                          </router-link>
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

const FilterWordCategory = {
    data: () => ({
      loadding: true,
      types: {},
      checked: false,
      errorMessage: '',
      successMessage: '',
    }),
    methods: {
      getTypes () {
        request.get(
          createRequestURI('filter-word-types'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.loadding = false;
          this.types = response.data;
        }).catch(({ response: { data = {} } = {} }) => {
          let {name = []} = data;
          let [ errorMessage ] = [...name];
          this.errorMessage = errorMessage;
        });
      },
      changeStatus (id) {
        request.patch(
          createRequestURI('filter-word-types/' + id + '/status'),
          { validateStatus: status => status === 200 }
        ).then(({ data: { message: [ message ] = [] } }) => {
            this.successMessage = message;
            this.getTypes();
        }).catch(({ response: { data = {} } = {} }) => {
          let {name = []} = data;
          let [ errorMessage ] = [...name];
          this.errorMessage = errorMessage;
        });
      },
      selectAll () {
        this.checked = this.checked ? false : true;
      },
      offAlert () {
        this.errorMessage = this.successMessage = '';
      },
    },
    created () {
      this.getTypes();
    },
};
export default FilterWordCategory;
</script>
