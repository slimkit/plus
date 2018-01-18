<template>
  <div>
    <h3>GitHub 账号</h3>
    <p class="page-setting-text" v-if="githubAccesses">
      您已绑定 GitHub 账号：{{ githubAccesses }} &nbsp;
      <el-button size="mini" type="danger">解绑</el-button>
    </p>
    <el-form size="medium" :inline="true" v-else>
      <el-form-item label="账号">
        <el-input placeholder="GitHub 账号" v-model="githubForm.login" required></el-input>
      </el-form-item>
      <el-form-item label="密码">
        <el-input type="password" placeholder="请输入密码" v-model="githubForm.password" required></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary">绑定</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import * as Setting from '../../api/setting';
import PlusMessageBundle from 'plus-message-bundle';
export default {
  name: 'page-setting',
  data: () => ({
    githubUsername: '',
    githubForm: { login: '', password: '' },
  }),
  methods: {
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
        });
      });
    }
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
