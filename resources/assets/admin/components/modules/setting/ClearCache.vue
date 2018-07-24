<template>
  <div class="panel panel-default">
    <!-- title -->
    <div class="panel-heading">缓存清理</div>

    <!-- body -->
    <div class="panel-body">
      <div class="alert alert-warning">
        如果没有特殊情况，请不要执行这个操作。执行后所有的缓存都将被删除！
      </div>
      <sb-ui-button
        class="btn btn-danger"
        label="清理缓存"
        proces-label="清理中..."
        @click="clearHandle"
      />
    </div>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../../util/request';
export default {
  methods: {
    clearHandle(event) {

      if (!window.confirm('【⚠️警告】👉是否确认执行？')) {
        event.stopProcessing();

        return;
      }

      request.get(createRequestURI('auxiliary/clear'), {
        validateStatus: status => status === 200,
      }).then(() => {
        this.$store.dispatch('alert-open', { type: 'success', message: { message: '清理成功' } });
        event.stopProcessing();
      }).catch(({ response: { data = { message: '清理失败！' } } }) => {
        event.stopProcessing();
        this.$store.dispatch('alert-open', {
          type: 'danger',
          message: data,
        });
      });
    }
  },
};
</script>
