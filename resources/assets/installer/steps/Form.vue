<template>
  <el-form ref="form" label-width="100px" label-position="left" @submit.native.prevent.stop="handleSubmit">

    <el-form-item label="应用名称">
      <el-input v-model="form.name"></el-input>
    </el-form-item>

    <el-form-item label="应用地址" required>
      <el-input v-model="form.url"></el-input>
    </el-form-item>

    <el-form-item label="数据库类型" required>
      <el-radio-group size="medium" v-model="form.databaseType">
        <el-radio border label="mysql">MySQL</el-radio>
        <el-radio border label="pgsql">PostgreSQL</el-radio>
      </el-radio-group>
    </el-form-item>

    <el-form-item label="数据库地址" required>
      <el-input v-model="form.host"></el-input>
    </el-form-item>

    <el-form-item label="数据库端口" required>
      <el-input v-model="form.port"></el-input>
    </el-form-item>

    <el-form-item label="数据库名称" required>
      <el-input v-model="form.database"></el-input>
    </el-form-item>

    <el-form-item label="数据库用户" required>
      <el-input v-model="form.username"></el-input>
    </el-form-item>

    <el-form-item label="数据库密码">
      <el-input v-model="form.dbPassword"></el-input>
    </el-form-item>

    <el-form-item>
      <el-button type="primary" :loading="submitting" @click="handleSubmit">设置信息</el-button>
    </el-form-item>

    <el-form-item>
      <el-alert
        v-show="alert.open"
        title=""
        :type="alert.type"
        :description="alert.message"
        :closable="false"
        show-icon>
      </el-alert>
    </el-form-item>

  </el-form>
</template>

<script>
import request from '../axios';
export default {
  name: 'step-form',
  props: {
    step: { type: Number, required: true },
    password: { type: String, required: true },
    handleStep: { type: Function, required: true },
  },
  data: () => ({
    form: {
      name: 'ThinkSNS+',
      url: null,
      databaseType: null,
      host: '127.0.0.1',
      port: 3600,
      database: 'plus',
      username: 'root',
      dbPassword: ''
    },
    submitting: false,
    alert: {
      open: false,
      type: 'error',
      message: '',
    },
  }),
  methods: {
    handleSubmit () {
      this.submitting = true;
      request.put('/info', { ...this.form, password: this.password }, {
        validateStatus: status => status === 204,
      }).then(() => {
        this.handleStep(this.step + 1, { form: 'success' });
      }).catch(({ response: { data: { message = '请求失败，请检查网络！' } = {} } = {} }) => {
        this.alert = { open: true, type: 'error', message };
        this.submitting = false;
      });
    }
  },
  created () {
    request.post('/info', { password: this.password }, {
      validateStatus: status => status === 200,
    }).then(({ data }) => {
      this.form = data;
    });
  }
};
</script>
