<template>
  <!-- 提交按钮 -->
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-4">
      <ui-button type="button" class="btn btn-primary" @click="handleSubmit"></ui-button>
    </div>
    <span class="col-sm-6 help-block">
      设置公开磁盘，储存目录为 storage/app/public 如果你切换了磁盘，请手动迁移文件。
    </span>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../../../util/request';
export default {
  name: 'module-cdn-filesystem-public',
  methods: {
    handleSubmit ({ stopProcessing }) {
      request.post(createRequestURI('cdn/filesystems/public'), {}, {
        validateStatus: status => status === 201,
      }).then(({ data }) => {
        stopProcessing();
        this.$store.dispatch('alert-open', { type: 'success', message: data });
      }).catch(({ response: { data = { message: '提交失败' } } = {} }) => {
        stopProcessing();
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
      });
    },
  },
};
</script>
