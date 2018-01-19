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
import github from '../../../api/github';
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
      const { github_owner, github_repo } = this.project;
      this.loading = true;
      github.get(`/repos/${github_owner}/${github_repo}/readme`, {
        validateStatus: status => status === 200,
        headers: {
          'Accept': 'application/vnd.github.v3.html',
          'Authorization': github.basicToken(),
        },
      }).then(({ data }) => {
        this.readme = data;
        this.loading = false;
      }).catch();
    },
  },
  created() {
    this.fetchReadme();
    console.log(this.project);
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
