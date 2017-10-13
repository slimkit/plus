<template>
    <div class="container-fluid" style="margin:15px;">
        <div v-show="message.success" class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.success }}
        </div>
        <div v-show="message.error" class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ message.error }}
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            金币类型列表
            <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/gold/types/add" role="button">
              <span class="glyphicon glyphicon-plus"></span>
              添加
            </router-link>
          </div>
          <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名称</th>
                        <th>单位</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                      <table-loading :loadding="loadding" :colspan-num="5"></table-loading>
                      <!-- 数据存在 -->
                      <template v-if="types.length">
                        <tr v-for="type in types">
                          <td>{{ type.id }}</td>
                          <td>{{ type.name }}</td>
                          <td>{{ type.unit }}</td>
                          <td>
                             <span :class="type.status ? 'text-success' : 'text-danger'">{{ type.status ? '启动' : '关闭' }}</span>
                          <td>
                          <template v-if="type.status !== 1">
                            <button class="btn btn-primary btn-sm" @click.prevent="changeStatus(type.id)">{{ !type.status ? '启动' : '关闭' }}</button>
                            <button class="btn btn-danger btn-sm" @click.prevent="delType(type.id)">删除</button>
                          </template>
                          </td>
                        </tr>
                      </template>
                      <template v-else-if="!loadding">
                        <!-- 数据为空 -->
                        <tr><td colspan="7" style="text-align:center;">无数据</td></tr>
                      </template>
                </tbody>
            </table>
          </div>
        </div>
    </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
const HomeComponent = {
    data: () => ({

      loadding: true,

      typs:[],
    
      message: {
        error: null,
        success: null,
      }
    
    }),

    methods: {

      getGoldTypes () {
        this.types = [];
        this.loadding = true;
        request.get(
          createRequestURI('gold/types'),
          { validateStatus: status => status === 200 }
        ).then(response => {
          this.types = response.data;
          this.loadding = false;
        }).catch(({ response: { data: { errors = ['获取金币类型失败'] } = {} } = {} }) => {
          this.message.error = errors;
          this.loadding = false;
        });
      },

      changeStatus (id) {
        request.patch(
          createRequestURI(`gold/types/${id}/open`),
          { validateStatus: status => status === 201 }
        ).then(({ data: { message: [ message ] = [] } }) => {

            this.getGoldTypes();
            this.message.success = message;

        }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {

          this.loadding = false;
          this.message.error = errors;

        });
      },

      delType (id) {
        request.delete(
          createRequestURI(`gold/types/${id}`),
          { validateStatus: status => status === 204 }
        ).then(({ data: { message: [ message ] = [] } }) => {

            this.getGoldTypes();
            this.message.success = '删除成功';

        }).catch(({ response: { data: { errors = ['删除失败'] } = {} } = {} }) => {

          this.message.error = errors;

        });
      },
    },

    created () {

      this.getGoldTypes();

    },
};

export default HomeComponent;
</script>
