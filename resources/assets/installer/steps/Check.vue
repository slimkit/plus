<template>
  <div>
    <el-alert
      class="check-alert"
      v-for="alert, index in messages"
      :key="index"
      title=""
      :type="alert.type"
      :description="alert.message"
      :closable="false"
      show-icon>
    </el-alert>

    <el-button type="primary" @click="handleCheck">重新检查</el-button>
    <el-button v-if="pass" type="primary" @click="handlePass">下一步</el-button>

  </div>
</template>

<script>
import request from '../axios';
export default {
  name: 'step-check',
  props: {
    step: { type: Number, required: true },
    password: { type: String, required: true },
    handleStep: { type: Function, required: true },
  },
  data: () => ({
    messages: [],
  }),
  computed: {
    pass () {
      for (let index in this.messages) {
        const message = this.messages[index];

        if (message.type != 'success') {
          return false;
        }
      }
      
      return true;
    }
  },
  methods: {
    handleCheck () {
      const loading = this.$loading({
        fullscreen: true,
        text: '环境检查中...',
      });
      request.post('/check', { password: this.password }).then(({ data }) => {
        loading.close();
        this.messages = data;
      }).catch(({ response: { data: { message = '请求检查数据失败' } = {} } = {} }) => {
        loading.close();
        this.messages = [{
          type: 'error',
          message,
        }];
      });
    },
    handlePass () {
      this.handleStep(this.step + 1, { check: 'success' });
    }
  },
  created () {
    this.handleCheck();
  }
};
</script>

<style>
.check-alert {
  margin: 12px auto;
}
</style>
