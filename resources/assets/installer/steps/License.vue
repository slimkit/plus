<template>
  <div v-if="! error">
    <pre class="license">{{ license }}</pre>
    <el-button class="block-center" type="primary" @click="handlePassLicense">同意协议</el-button>
  </div>
  <div v-else>
    <el-alert show-icon type="error" :closable="false" title="">
      {{ error }}
    </el-alert>
    <br />
    <el-button class="block-center" type="primary" @click="handleGetLicense">重新获取</el-button>
  </div>
</template>

<script>
import request from '../axios';
export default {
  name: 'step-license',
  props: {
    step: { type: Number, required: true },
    handleStep: { type: Function, required: true },
  },
  data: () => ({
    license: null,
    error: null,
  }),
  methods: {
    handleGetLicense () {
      const loading = this.$loading({
        fullscreen: true,
        text: '获取授权协议中...',
      });
      request.get('/license', {
        validateStatus: status => status === 200,
      }).then(({ data: license }) => {
        loading.close();
        this.license = license;
      }).catch(({ response: { data: { message = '获取授权协议失败！' } = {} } = {} }) => {
        loading.close();
        this.error = message;
      });
    },
    handlePassLicense () {
      this.handleStep(this.step + 1, { license: 'success' });
    },
  },
  created () {
    this.handleGetLicense();
  }
};
</script>

<style>
.license {
  width: 100%;
  max-height: 300px;
  padding: 20px;
  overflow: hidden;
  overflow-y: scroll;
}
.block-center {
  display: block;
  margin: auto;
}
</style>
