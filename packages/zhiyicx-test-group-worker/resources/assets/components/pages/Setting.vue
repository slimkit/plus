<template>
  <div v-if="loadingError">
    <el-alert
      title="当前页面无加载数据"
      type="error"
      :closable="false">
    </el-alert>
  </div>
  <div v-else>
    <h3>GitHub 账号</h3>
    <p class="page-setting-text" v-if="githubUsername">
      您已绑定 GitHub 账号：{{ githubUsername }} &nbsp;
      <el-button size="mini" type="danger">解绑</el-button>
    </p>
    <el-form size="medium" :inline="true" v-else>
      <el-form-item label="账号">
        <el-input placeholder="GitHub 账号" v-model="githubForm.login" required :disabled="githubForm.loading"></el-input>
      </el-form-item>
      <el-form-item label="密码">
        <el-input type="password" placeholder="请输入密码" v-model="githubForm.password" required :disabled="githubForm.loading"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button v-if="githubForm.loading" type="primary" :loading="true">绑定中...</el-button>
        <el-button v-else type="primary" @click="handleBindAccess">绑定</el-button>
      </el-form-item>
      <el-alert
        title="因为 GitHub API 要求限制，需要使用 basic 认证。"
        type="info"
        close-text="知道了"
        show-icon>
      </el-alert>
    </el-form>
  </div>
</template>

<script>
import * as Setting from '../../api/setting';
import PlusMessageBundle from 'plus-message-bundle';
export default {
  name: 'page-setting',
  data: () => ({
    loadingError: false,
    githubUsername: '',
    githubForm: { login: '', password: '', loading: false },
  }),
  methods: {
    /**
     * Fetch all setting data.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    fetchSettings() {
      const loading = this.$loading({
        fullscreen: true,
        text: '正在加载设置数据...',
      });
      Setting.settings().then(({ data }) => {
        loading.close();
        const {
          ['github-access']: githubUsername
        } = data;
        this.githubUsername = githubUsername;
      }).catch(({ response: { data = {} } = {} }) => {
        loading.close();
        const Message = new PlusMessageBundle(data, '加载数据失败，是否重试？');
        this.$confirm(Message.getMessage(), '错误', {
          confirmButtonText: '重试',
          cancelButtonText: '取消',
          type: 'error',
          center: true,
        }).then(this.fetchSettings).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消加载数据',
          });
          this.loadingError = true;
        });
      });
    },

    /**
     * Bind GitHub access handle.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleBindAccess() {
      const { login, password } = this.githubForm;
      this.githubForm.loading = true;
      Setting.githubBind(login, password).then(({ data }) => {
        const { username, message } = data;
        this.githubForm = { loading: false, login: '', password: '' };
        this.githubUsername = username;
         this.$notify({
          title: '成功',
          message: `添加 GitHub 账号「${username}」成功`,
          type: 'success',
        });
      }).catch(({ response: { data = {} } = {} }) => {
        this.githubForm.loading = false;
        const Message = new PlusMessageBundle(data, '添加 GitHub 账号失败');
        this.$notify({
          title: '错误',
          message: Message.getMessage(),
          type: 'error',
        });
      });
    },
  },
  created() {
    this.fetchSettings();
  }
};
</script>

<style>
.page-setting-text {
  color: #5e6d82;
}
</style>
