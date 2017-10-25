<template>
  <ui-loading v-if="loading"></ui-loading>
  <div v-else>
    
    <!-- 公开地址 -->
    <div class="form-group">
      <label class="col-sm-2 control-label">公开地址</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="请输入可访问的公开地址" v-model="public">
      </div>
      <span class="col-sm-6 help-block">本地磁盘不会将文件存放在公开目录，文件将存放在 storage/app 目录下，你需要使用其他方式部署静态资源访问服务，部署完成后将访问地址填入此处。</span>
    </div>

    <!-- 提交按钮 -->
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <ui-button type="button" class="btn btn-primary" @click="handleSubmit"></ui-button>
      </div>
    </div>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../../../util/request';
export default {
  name: 'module-cdn-filesystem-local',
  data: () => ({
    loading: false,
    public: null,
  }),
  methods: {
    handleSubmit ({ stopProcessing }) {
      request.post(createRequestURI('cdn/filesystems/local'), { public: this.public }, {
        validateStatus: status => status === 201,
      }).then(({ data }) => {
        this.$store.dispatch('alert-open', { type: 'success', message: data });
        stopProcessing();
      }).catch(({ response: { data = { message: '提交失败！' } } = {} }) => {
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
        stopProcessing();
      });
    }
  },
  created () {
    this.loading = true;
    request.get(createRequestURI('cdn/filesystems/local'), {
      validateStatus: status => status === 200,
    }).then(({ data }) => {
      this.loading = false;
      this.public = data.public;
    }).catch(({ response: { data = { message: '加载失败，请刷新重试！' } } = {} }) => {
      this.$store.dispatch('alert-open', { type: 'danger', message: data });
    });
  }
};
</script>
