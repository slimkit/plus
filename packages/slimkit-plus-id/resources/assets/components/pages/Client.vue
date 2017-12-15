<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      客户端列表
      <router-link :to="{ path: '/client-add' }" class="pull-right" replace>
        <span class="glyphicon glyphicon-plus"></span>
        添加
      </router-link>
    </div>

    <div class="panel-body" v-if="loadding">
      <bootstrap-ui-kit:ui-loading></bootstrap-ui-kit:ui-loading>加载中...
    </div>

    <table v-else class="table table-striped table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>名称</th>
          <th>地址</th>
          <th>密钥</th>
          <!-- <th>状态</th> -->
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <module-client-row v-for="client in clients" :key="client.id" :client="client"></module-client-row>
      </tbody>
    </table>

  </div>
</template>

<script>
import { admin } from '../../axios';
import ClientRow from '../modules/ClientRow';
export default {
  name: 'client',
  components: { [ClientRow.name]: ClientRow },
  data: () => ({
    clients: [],
    loadding: false,
  }),
  methods: {
    fetchClients () {
      this.loadding = true;
      admin.get('/clients', {
        validateStatus: status => status === 200,
      }).then(({ data: clients }) => {
        this.loadding = false;
        this.clients = clients;
      }).catch(({ response: { data = { message: '加载失败...' } } = {} }) => {
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
      });
    }
  },
  created () {
    this.fetchClients();
  }
};
</script>
