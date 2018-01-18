<template>
  <div class="panel-body">
    <ui-loading v-if="loading"></ui-loading>
    <div v-else class="form-horizontal">
      
      <!-- 选择驱动 -->
      <module-cdn-select :handle-select="handleSelect" value="filesystem"></module-cdn-select>

      <!-- 磁盘选择 -->
      <module-cdn-filesystem-disk :disk="disk" @change="handleSelectDisk"></module-cdn-filesystem-disk>

      <!-- 磁盘 -->
      <module-cdn-filesystem-local v-if="disk === 'local'"></module-cdn-filesystem-local>
      <module-cdn-filesystem-public v-else-if="disk === 'public'"></module-cdn-filesystem-public>
      <module-cdn-filesystem-s3 v-else-if="disk === 's3'"></module-cdn-filesystem-s3>

    </div>
  </div>
</template>

<script>
import Select from './Select';
import components from './filesystems';
import request, { createRequestURI } from '../../../util/request';
export default {
  name: 'module-cdn-filesystem',
  components: {
    ...components,
    [Select.name]: Select,
  },
  props: {
    handleSelect: { type: Function, required: true },
  },
  data: () => ({
    disk: 'public',
    loading: false,
  }),
  methods: {
    handleSelectDisk (disk) {
      this.disk = disk;
    },
  },
  created () {
    this.loading = true;
    request.get(createRequestURI('cdn/filesystem/disk'), {
      validateStatus: status => status === 200,
    }).then(({ data: { disk = 'public' } }) => {
      this.disk = disk;
      this.loading = false;
    }).catch(({ response: { data = { message: '加载失败，请刷新重试！' } } = {} }) => {
      this.loading = false;
      this.$store.dispatch('alert-open', { type: 'danger', message: data });
    });
  }
};
</script>
