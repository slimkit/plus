<template>
  <div class="panel-body">
    <div class="form-horizontal">
      
      <!-- 选择驱动 -->
      <module-cdn-select :handle-select="handleSelect" value="local"></module-cdn-select>

      <!-- 磁盘选择 -->
      <module-cdn-filesystem-disk></module-cdn-filesystem-disk>

      <!-- 提交按钮 -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">

          <ui-button type="button" class="btn btn-primary" @click="handleSubmit"></ui-button>

        </div>
      </div>

    </div>
  </div>
</template>

<script>
import Select from './Select';
import components from './filesystems';
import request, { createRequestURI } from '../../../util/request';
export default {
  name: 'module-cdn-local',
  components: {
    ...components,
    [Select.name]: Select,
  },
  props: {
    handleSelect: { type: Function, required: true },
  },
  data: () => ({
    disk: 'public',
  }),
  methods: {
    handleSubmit ({ stopProcessing }) {
      request.post(createRequestURI('cdn/local'), { cdn: 'local' }, {
        validateStatus: status => status === 201,
      }).then(({ data }) => {
        stopProcessing();
        this.$store.dispatch('alert-open', { type: 'success', message: data });
      }).catch(({ response: { data = { message: '提交失败' } } }) => {
        stopProcessing();
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
      });
    }
  },
};
</script>
