<template>
  <div id="app">
    <header class="header">
      <div class="container">
        <img v-if="logo" :src="logo">
        <h1 v-else>ThinkSNS Plus</h1>
        <span>当前版本：{{ version }}</span>
      </div>
    </header>
    <main class="container">
      <el-card class="main-step">
        <el-steps :active="step" direction="horizontal" :align-center="true">
          <el-step title="安装密码" :status="status.password"></el-step>
          <el-step title="授权协议" :status="status.license"></el-step>
          <el-step title="环境检查" :status="status.check"></el-step>
          <el-step title="基础信息" :status="status.form"></el-step>
          <el-step title="数据库" :status="status.database"></el-step>
          <el-step title="完成" :status="status.done"></el-step>
        </el-steps>

        <div class="main">
          <step-password v-if="step === 0" :step="step" :handle-step="handleStep" :handle-password="handlePassword"></step-password>
          <step-license v-else-if="step === 1" :step="step" :handle-step="handleStep"></step-license>
          <step-check v-else-if="step === 2" :step="step" :handle-step="handleStep" :password="password"></step-check>
          <step-form v-else-if="step === 3" :step="step" :handle-step="handleStep" :password="password"></step-form>
          <step-database v-else-if="step === 4" :step="step" :handle-step="handleStep"></step-database>
          <step-done v-else-if="step === 5"></step-done>
        </div>
        
      </el-card>
    </main>
    <footer class="container">
      <hr class="footer-hr" />
      <p class="copyright">
        © {{ (new Date).getFullYear() }} Chengdu ZhiYiChuangXiang Technology Co., Ltd. All rights reserved.
      </p>
    </footer>
  </div>
</template>

<script>
import components from './steps';
export default {
  name: 'app',
  components,
  computed: {
    /**
     * Get logo href.
     *
     * @return {String|null}
     * @author Seven Du <shiweidu@outlook.com>
     */
    logo () {
      const logo = document.head.querySelector('meta[name="logo"]');

      return logo ? logo.content : null;
    },

    /**
     * Get version.
     *
     * @return {String|null}
     * @author Seven Du <shiweidu@outlook.com>
     */
    version () {
      const version = document.head.querySelector('meta[name="version"]');

      return version ? version.content : null
    }
  },

  /**
   * Set the root component data.
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   */
  data: () => ({
    step: 0,
    status: {
      password: null,
      license: null,
      check: null,
      form: null,
      database: null,
      done: null,
    },
    password: '123456',
  }),

  methods: {
    /**
     * Handle set step and status.
     *
     * @param {Number} step
     * @param {Object} status
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleStep (step, status = {}) {
      this.step = step;
      this.status = { ...this.status, ...status };
    },

    /**
     * Set password handle.
     *
     * @param {String} password
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handlePassword (password) {
      this.password = password;
    }
  },
};
</script>

<style>
html, body {
  padding: 0;
  margin: 0;
  font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
}

#app {
  background-color: #fff;
}

.header {
  background-color: #1a9fff;
  height: 300px;
}
.header > .container {
  height: 152px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #fff;
  flex-flow: row;
}
.header > .container > img {
  width: auto;
  height: 100px;
}

.main-step {
  margin-top: -148px;
}

.main {
  padding: 24px 36px 0;
}

footer.container {
  text-align: center;
}

.footer-hr {
  width: 100px;
  margin: 40px auto;
  border-color: rgba(255,255,255,0.1);
  height: 0;
  overflow: hidden;
  background: transparent;
  border: 0;
  border-bottom: 1px solid #ddd;
  box-sizing: content-box;
}

.container {
  width: 1000px;
  margin: 0 auto;
}
</style>
