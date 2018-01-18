<template>
  <el-container direction="vertical" id="root" class="app" :style="appStyle">
    <el-header class="app-header">
      <span class="app-title">Test Group Worker</span>
      <el-dropdown trigger="click">
        <img class="user-avatar" :src="user.avatar" :alt="user.name" />
        <el-dropdown-menu slot="dropdown">
          <el-dropdown-item @click.native="handleUserLogout">退出登录</el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </el-header>
    <el-container direction="horizontal" class="app-container">
      <el-aside width="240px">
        <el-menu :router="true" :default-active="menuActive">
          <el-menu-item index="/projects">
            <i class="el-icon-menu"></i>
            <span slot="title">项目管理</span>
          </el-menu-item>
          <el-menu-item index="/tasks">
            <i class="el-icon-tickets"></i>
            <span slot="title">待处理任务</span>
          </el-menu-item>
          <el-menu-item index="/setting">
            <i class="el-icon-setting"></i>
            <span slot="title">设置</span>
          </el-menu-item>
        </el-menu>
      </el-aside>
      <el-container direction="vertical">
        <el-main>
          <router-view></router-view>
          <el-footer class="app-footer">
            © 2018 Chengdu ZhiYiChuangXiang Technology Co., Ltd. All rights reserved.
          </el-footer>
        </el-main>
      </el-container>
    </el-container>
  </el-container>
</template>

<script>
export default {
  name: 'app',
  data: () => ({
    innerHeight: '100vh',
    user: window.user,
  }),
  computed: {
    appStyle() {
      return 'height: ' + this.innerHeight;
    },
    menuActive() {
      return this.$route.path;
    },
    logoutUrl() {
      return window.logoutUrl;
    }
  },
  methods: {
    handleUserLogout() {
      window.location.href = window.logoutUrl;
    }
  },
  created() {
    window.onresize = () => {
      this.innerHeight = window.innerHeight + 'px';
    };
    this.innerHeight = window.innerHeight + 'px';
  }
};
</script>

<style>
.app {
  width: 100%;
  height: 100vh;
}
.app-header {
  width: 100%;
  height: 60px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: solid 1px #e6e6e6;
}
.app-container {
  width: 1140px;
  margin: 0 auto;
}
.app-title {
  color: #888;
  text-decoration: none;
}
.app-title:hover {
  color: #333;
  cursor: pointer;
}
.app-footer {
  height: 60px;
  line-height: 60px;
  text-align: center;
  color: #8c8c8c;
}
.user-avatar {
  width: 34px;
  height: 34px;
  cursor: pointer;
  outline: none;
}
</style>
