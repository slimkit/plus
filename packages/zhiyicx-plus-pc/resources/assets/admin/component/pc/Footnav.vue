<template>
  <div class="panel panel-default">
    <div class="panel-heading">底部导航
      <router-link :to="{ path: '/navmanage' }" class="pull-right" style="margin-right: 20px;" replace>
          <span class="glyphicon glyphicon-plus"></span> 添加导航
      </router-link>
    </div>
    <div class="panel-body">
    <!-- 加载动画 -->
    <div v-show="loadding" class="panel-body text-center">
      <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
      加载中...
    </div>
    <!-- 整体盒子 -->
    <div v-show="!loadding" class="">
      <!-- 列表表格 -->
      <table class="table table-hover table-bordered table-responsive table-middle table-news">
        <thead>
          <tr>
            <th>导航名称</th>
            <th>英文名称</th>
            <th>链接地址</th>
            <th>打开方式</th>
            <th>状态</th>
            <th>导航位置</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
            <tr v-for="nav in list">
              <td align="left">
                <div v-if="nav.parent_id" class="input-group" style="margin-left: 10px;"> ┗  {{ nav.name }}</div>
                <div v-else class="input-group">{{ nav.name }}</div>
              </td>
              <td align="center"><div class="input-group">{{ nav.app_name }}</div></td>
              <td align="left"><div class="input-group">{{ nav.url }}</div></td>
              <td align="center"><div class="input-group">{{ targets[nav.target] }}</div></td>
              <td align="center"><div class="input-group">{{ state[nav.status] }}</div></td>
              <td align="center"><div class="input-group">{{ types[nav.position] }}</div></td>
              <td align="left">
                <div class="btn-group" style="margin: 5px 0;">
                  <router-link type="button" class="btn btn-primary btn-sm" :to="'/navmanage/'+nav.id+'/0'">编辑</router-link>
                  <button type="button" class="btn btn-danger btn-sm" @click.prevent="deletenav(nav.id)">删除</button>
                </div>
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

const navComponent = {
  data: () => ({
    current: 0,
    loadding: true,
    list: {},
    targets: {
      _blank: '新窗口',
      _self: '当前窗口',
      _parent: '父窗口'
    },
    types: {
      0: '头部',
      1: '底部'
    },
    state: {
      0: '关闭',
      1: '开启'
    }
  }),
  methods: {
    selectCurrent (id) {

    },
    deletenav (id) {
      if (window.confirm('确认删除?')) {
          request.delete(
            createRequestURI('nav/del/'+id),
            { validateStatus: status => status === 200 }
          ).then(({ message = '删除成功' }) => {
            let index;
            this.list.find(function(v, k){
              if (v.id == id) index = k;
            });
            this.loadding = false;
            this.list.splice(index, 1);
          }).catch(({ response: { message = '删除失败' } = {} }) => {
            this.loadding = false;
            this.message = message;
          });
      }
    }
    },
    created () {
        this.loadding = true;
        request.get(
          createRequestURI('nav/list/1'),
          { validateStatus: status => status === 200 }
        ).then(({ data: { data = {} } }) => {
          this.loadding = false;
          this.list = data;
        }).catch(({ response: { data: { message: [ message = '加载失败' ] = [] } = {} } = {} }) => {
          this.loadding = false;
          this.message = message;
        });
    }
};

export default navComponent;
</script>
<style type="text/css" media="screen">
.table-middle td {
  text-align: center;
  vertical-align: middle !important;
}
</style>