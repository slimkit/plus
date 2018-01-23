<template>
  <div>
    <el-row type="block" :gutter="24">

      <el-col :span="12">
        <el-card>
          <div slot="header">
            <span>新建一个项目</span>
            <el-button style="float: right; padding: 3px 0" type="text" @click="handleCreateProject">立即创建</el-button>
          </div>
          <el-form label-position="right" label-width="80px">
            <el-form-item label="项目名称">
              <el-input v-model="createForm.name" type="text" placeholder="请输入项目名称"></el-input>
            </el-form-item>
            <el-form-item label="项目描述">
              <el-input v-model="createForm.desc" type="textarea" :rows="3" placeholder="请输入项目描述"></el-input>
            </el-form-item>
          </el-form>
        </el-card>
      </el-col>

      <el-col :span="12" v-for="project in projects" :key="project.id">
        <el-card class="project-card">
          <div slot="header">{{ project.name }}</div>

          <el-tooltip content="完成的测试任务" placement="top">
            <el-button type="text" class="project-button" icon="el-icon-circle-check">{{ project.task_completed_count }}</el-button>
          </el-tooltip>
          <el-tooltip content="总测试任务" placement="top">
            <el-button type="text" class="project-button" icon="el-icon-tickets">{{ project.task_count }}</el-button>
          </el-tooltip>
          <el-tooltip content="Issues 数量" placement="top">
            <el-button type="text" class="project-button" icon="el-icon-warning">{{ project.issues_count }}</el-button>
          </el-tooltip>

          <div class="project-desc">
            {{ project.desc }}
            <p>
              <el-button @click="handleGoProjectHome(project.id)">进入项目</el-button>
            </p>
          </div>

        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import * as Projects from '../../api/projects';
import PlusMessageBundle from 'plus-message-bundle';
export default {
  name: 'page-projects',
  data: () => ({
    createForm: { name: '', desc: '' },
    projects: [],
  }),
  methods: {
    handleGoProjectHome(id) {
      this.$router.push(`/projects/${id}`);
    },
    /**
     * Create project goto handle.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleCreateProject() {
      let query = {};
      const { name, desc } = this.createForm;
      if (name) {
        query['name'] = name;
      }
      if (desc) {
        query['desc'] = desc;
      }
      this.$router.push({
        path: '/new-project',
        query,
      });
    },

    /**
     * Fetch all projects.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    fetchProjects() {
      const loading = this.$loading({
        fullscreen: true,
        text: '正在获取项目列表...',
      });
      Projects.all().then(({ data }) => {
        this.projects = data;
        loading.close();
      }).catch(({ response: { data = {} } = {} }) => {
        loading.close();
        const Message = PlusMessageBundle(data, '获取项目列表失败, 是否重试？');
        this.$confirm(Message.getMessage(), '错误', {
          confirmButtonText: '重试',
          cancelButtonText: '取消',
          type: 'error',
          center: true,
        }).then(this.fetchProjects).catch(() => {
          this.$message({
            type: 'info',
            message: '已取消加载数据',
          });
          this.loadingError = true;
        });
      });
    },
  },
  created() {
    this.fetchProjects();
  }
};
</script>

<style>
.project-card {
  text-align: center;
  margin-bottom: 20px;
}
.project-button {
  padding: 0;
  font-weight: lighter;
  margin-right: 20px;
  margin-bottom: 20px;
}
.project-button+.project-button {
  margin-right: 20px;
}
.project-desc {
  font-size: 14px;
  color: #99a9bf;
}
</style>
