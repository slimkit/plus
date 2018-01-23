<template>
  <div>
    <header class="project-header" :style="`background: ${headerBackgroundColor};`">
      <h3>{{ project.name }}</h3>
      <a :href="githubRepoLink" target="_blank">
        <el-button type="text" class="repo-link">{{ githubRepoLink }}</el-button>
      </a>
      <div class="count clearfix">
        <el-tooltip content="完成的测试任务" placement="top">
          <el-button type="text" class="project-button" icon="el-icon-circle-check">{{ project.task_completed_count }}</el-button>
        </el-tooltip>
        <el-tooltip content="总测试任务" placement="top">
          <el-button type="text" class="project-button" icon="el-icon-tickets">{{ project.task_count }}</el-button>
        </el-tooltip>
        <el-tooltip content="Issues 数量" placement="top">
          <el-button type="text" class="project-button" icon="el-icon-warning">{{ project.issues_count }}</el-button>
        </el-tooltip>
      </div>
    </header>
    <el-menu mode="horizontal" :default-active="$route.path" :active-text-color="headerBackgroundColor" router>
      <el-menu-item :index="`/projects/${projectId}`">General</el-menu-item>
      <a class="el-menu-item issues-link" :href="`${githubRepoLink}/issues`" target="_blank">Issues</a>
      <!-- <el-menu-item :index="`/projects/${projectId}/issues`">Issues</el-menu-item> -->
      <!-- <el-menu-item :index="`/projects/${projectId}/tasks`">Test Tasks</el-menu-item> -->
      <el-submenu index="tasks">
        <template slot="title">Test Task</template>
        <el-menu-item :index="`/projects/${projectId}/tasks`">Lists</el-menu-item>
        <el-menu-item :index="`/projects/${projectId}/new-tasks`">Create Tesk</el-menu-item>
      </el-submenu>
      <el-menu-item :index="`/projects/${projectId}/settings`">Settings</el-menu-item>
    </el-menu>
    <router-view v-if="loaded" :project="project" :color="headerBackgroundColor"></router-view>
  </div>
</template>

<script>
import * as Projects from '../../api/projects';
import PlusMessageBundle from 'plus-message-bundle';
const backgroundColor = [ '#F44336', '#E91E63', '#9C27B0', '#673AB7', '#3F51B5', '#2196F3', '#03A9F4', '#00BCD4', '#009688', '#4CAF50', '#8BC34A', '#CDDC39', '#FFC107', '#FF9800', '#FF5722', '#795548', '#9E9E9E', '#607D8B' ];
export default {
  name: 'page-project',
  data: () => ({
    loaded: false,
    project: {},
  }),
  computed: {
    /**
     * Get header background color.
     *
     * @return {string}
     * @author Seven Du <shiweidu@outlook.com>
     */
    headerBackgroundColor() {
      return backgroundColor[
        Math.floor((Math.random() * backgroundColor.length))
      ];
    },

    /**
     * Get the project id.
     *
     * @return {number}
     * @author Seven Du <shiweidu@outlook.com>
     */
    projectId() {
      return this.$route.params.id;
    },

    githubRepoLink() {
      if (! this.loaded) {
        return null;
      }

      const { github_owner, github_repo } = this.project;

      return `https://github.com/${github_owner}/${github_repo}`;
    }
  },
  methods: {
    /**
     * Fetch project data.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    fetchProject() {
      const loading = this.$loading({
        text: '正在加载项目信息...',
        fullscreen: true,
      });
      Projects.show(this.projectId).then(({ data }) => {
        loading.close();
        this.project = data;
        this.loaded = true;
      }).catch(({ response: { data = {} } = {} }) => {
        loading.close();
        const Message = PlusMessageBundle(data, '请求失败，请重试');
        this.$confirm(Message.getMessage(), '错误', {
          confirmButtonText: '重试',
          cancelButtonText: '取消',
          type: 'error',
          center: true,
        }).then(this.fetchProject).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消加载数据',
          });
          this.$router.back();
        });
      });
    },
  },
  /**
   * The Vue component created hook method.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  created() {
    this.fetchProject();
  }
};
</script>

<style lang="scss">
.project-header {
  width: 100%;
  color: #fff;
  padding: 12px 56px;
  box-sizing: border-box;
  margin-bottom: 12px;
  h3 {
    font-size: 32px;
    font-weight: 300;
    text-align: center;
  }
  .count {
    display: inline-block;
    float: right;
    margin-right: 24px;
    color: #fff;
    button {
      color: #fff !important;
    }
  }
  a {
    outline: none;
    text-decoration: none;
    .repo-link {
      color: #fff;
    }
  }
}
.issues-link {
  text-decoration: none;
}
</style>
