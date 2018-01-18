<template>
  <el-form ref="form" label-width="120px">
    <!-- 名称 -->
    <el-form-item label="项目名称">
      <el-input type="text" placeholder="请输入项目名称" v-model="createForm.name"></el-input>
    </el-form-item>

    <!-- 描述 -->
    <el-form-item label="项目描述">
      <el-input type="textarea" :rows="3" placeholder="请输入项目描述" v-model="createForm.desc"></el-input>
    </el-form-item>

    <!-- GitHub 地址 -->
    <el-form-item label="GitHub 仓库">
      <el-input placeholder="owner/repo" v-model="createForm.owner_repo">
        <template slot="prepend">https://github.com/</template>
      </el-input>
    </el-form-item>

    <!-- 资源分支 -->
    <el-form-item label="资源分支">
      <el-input type="text" placeholder="请输入资源分支名称" v-model="createForm.branch"></el-input>
      <p>
        <el-alert
        title="为什么需要填写资源分支？"
        description="因为使用平台进行 Issus 管理等有文件需求，这个分支专门用于储存静态文件。"
        type="info"
      >
      </el-alert>
      </p>
    </el-form-item>

    <el-form-item>
      <el-button type="primary" @click="handleSubmit">创建项目</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import * as Projects from '../../api/projects';
import PlusMessageBundle from 'plus-message-bundle';
export default {
  name: 'page-new-project',
  data: () => ({
    createForm: {
      name: '',
      desc: '',
      owner_repo: '',
      branch: 'master',
    },
  }),
  methods: {
    /**
     * Create project handle.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleSubmit() {
      const loading = this.$loading({
        fullscreen: true,
        text: '正在创建项目...',
      });
      Projects.store(this.createForm).then(({ data }) => {
        loading.close();
        const { id } = data;
        this.$router.replace(`/projects/${id}`);
      }).catch(({ response: { data = {} } = {} }) => {
        loading.close();
        const Message = new PlusMessageBundle(data, '请求失败，请重试');
        this.$notify({
          title: '错误',
          message: Message.getMessage(),
          type: 'error',
        });
      });
    },
  },
};
</script>
