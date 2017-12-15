<template>
  <el-form label-width="100px" :model="form" label-position="left" @submit.native.prevent.stop="handleSubmit">
    <el-form-item label="密码" :error="error" required>
      <el-input v-model="form.password" @focus="handleFocus" type="password" placeholder="请输入安装密码..."></el-input>
    </el-form-item>
    <el-form-item>
      <el-button type="primary" :loading="submitting" @click="handleSubmit">进入安装</el-button>
    </el-form-item>
    <el-form-item>
      <el-alert show-icon type="info" :closable="false" title="">
        如果你不知道你的安装密码，请执行 <code>php artisan install:password</code> 命令重置安装密码。
      </el-alert>
    </el-form-item>
  </el-form>
</template>

<script>
import request from '../axios';
export default {
  name: 'step-password',
  props: {
    step: { type: Number, required: true },
    handleStep: { type: Function, required: true },
    handlePassword: { type: Function, required: true },
  },
  /**
   * tThe component data.
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   */
  data: () => ({
    form: {
      password: null,
    },
    error: null,
    submitting: false,
  }),
  methods: {
    /**
     * Submitting password.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleSubmit () {
      this.submitting = true;
      request.post('/password', this.form, {
        validateStatus: status => status === 204
      }).then(() => {
        this.handlePassword(this.form.password);
        this.handleStep(this.step + 1, { password: 'success' });
      }).catch(({ response: { data: { message = '请求发送失败' } = {} } = {} }) => {
        this.submitting = false;
        this.error = message;
      });
    },
    /**
     * The password input focus set formatting to true.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleFocus () {
      this.error = null;
    },
  },
};
</script>
