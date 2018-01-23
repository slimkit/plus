<template>
  <div class="module-project-general">
    <div class="desc" :style="`background: ${color};`">{{ project.desc }}</div>
    <el-card v-loading="loading">
      <div slot="header">readme</div>
      <div class="markdown-body" v-html="readme"></div>
    </el-card>
  </div>
</template>

<script>
import { readme } from '../../../api/projects';
import PlusMessageBundle from 'plus-message-bundle';
export default {
  name: 'module-project-general',
  props: {
    project: { required: true, type: Object },
    color: { required: true, type: String },
  },
  data: () => ({
    readme: '',
    loading: true,
  }),
  methods: {
    /**
     * Fetch Repo readme.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    fetchReadme() {
      this.loading = true;
      readme(this.project.id).then(({ data }) => {
        this.readme = data.readme;
        this.loading = false;
      }).catch(({ response: { data = {} } = {} }) => {
        this.loading = true;
        const Message = PlusMessageBundle(data, '加载项目失败');
        this.$notify({
          title: '错误',
          message: Message.getMessage(),
          type: 'error',
        });
      });
    },
  },
  created() {
    this.fetchReadme();
  }
};
</script>

<style lang="scss">
.module-project-general {
  width: 100%;
  height: auto;
  .desc {
    margin: 24px auto;
    padding: 24px;
    text-align: center;
    color: #fff;
  }
}
</style>
