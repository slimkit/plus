<template>
  <div class="panel panel-default">
    <div class="panel-heading">添加客户端</div>
    <div class="panel-body">
      <div class="form-horizontal">
        
        <!-- name -->
        <div class="form-group">
          <label class="col-sm-2 control-label">名称</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" placeholder="请输入客户端名称" v-model="form.name">
          </div>
          <span class="col-sm-4 help-block">
            请输入客户端的名称。
          </span>
        </div>

        <!-- url -->
        <div class="form-group">
          <label class="col-sm-2 control-label">地址</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" placeholder="请输入客户端地址" v-model="form.url">
          </div>
          <span class="col-sm-4 help-block">
            请输入客户端接口地址。
          </span>
        </div>

        <!-- key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">密钥</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" placeholder="请输入密钥" v-model="form.key">
          </div>
          <span class="col-sm-4 help-block">
            请输入与客户端的通信密钥。
          </span>
        </div>

        <!-- 提交按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <bootstrap-ui-kit:ui-button type="button" class="btn btn-primary" proces-lable="添加中..." lable="添加" @click="handleSubmit"></bootstrap-ui-kit:ui-button>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { admin } from '../../axios';
export default {
  name: 'client-create',
  data: () => ({
    form: {
      name: '',
      key: '',
      url: '',
      sync_login: true,
    },
  }),
  methods: {
    handleSubmit ({ stopProcessing }) {
      admin.post('/clients', this.form, {
        validateStatus: status => status === 201,
      }).then(() => {
        this.$router.push('/');
      }).catch(({ response: { data = { message: '添加失败...' } } = {} }) => {
        stopProcessing();
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
      });
    }
  },
};
</script>
