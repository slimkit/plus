<template>
<div class="container-fluid">
  <div class="panel panel-default">
    
    <!-- title -->
    <div class="panel-heading">
      停用用户管理
      <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/users" role="button">
        返回
      </router-link>
    </div>

    <!-- loading -->
    <div class="panel-body" v-if="loading">
      <sb-ui-loading></sb-ui-loading>
    </div>

    <!-- table -->
    <table class="table" v-else>
      <thead>
        <tr>
          <th>用户 ID</th>
          <th>用户名</th>
          <th>邮箱</th>
          <th>手机号码</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.phone }}</td>
          <td>
            <sb-ui-button
              class="btn btn-warning btn-sm"
              label="恢复"
              proces-label="恢复中..."
              @click="event => { handleRestoreEventCallable(event, user.id); }"
            >
            </sb-ui-button>
          </td>
        </tr>
      </tbody>
    </table>

  </div>
</div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
export default {
  data: () => ({
    users: [],
    loading: true,
    handling: false,
  }),
  methods: {
    handleRestoreEventCallable(event, user) {
      if (this.handling) {
        this.$store.dispatch('alert-open', {
          type: 'warning',
          message: '正在执行其他恢复操作，请稍等！',
        });
        event.stopProcessing();
        return;
      }

      this.handling = true;
      this.handleRestore(event, user, () => {
        this.handling = false;
        event.stopProcessing();
      });
    },
    handleRestore(event, user, finallyCallable) {
      request.delete(createRequestURI('trashed-users/'+user), {
        validateStatus: status => status === 204,
      }).then(() => {
        this.$store.dispatch('alert-open', {
          type: 'success',
          message: '恢复成功！',
        });
        this.handleLoadUsers();
      }).catch(({ response: { data: message = '恢复失败！' } }) => {
        this.$store.dispatch('alert-open', { type: 'danger', message });
      }).finally(finallyCallable);
    },
    handleLoadUsers() {
      request.get(createRequestURI('trashed-users'), {
        validateStatus: status => status === 200,
      }).then(({ data }) => {
        this.users = data;
        this.loading = false;
      }).catch(({ response: { data: message = '请求失败！请刷新重试！' } = {} }) => {
        this.$store.dispatch('alert-open', { type: 'danger', message });
      });
    },
  },
  created() {
    this.handleLoadUsers();
  },
};
</script>
