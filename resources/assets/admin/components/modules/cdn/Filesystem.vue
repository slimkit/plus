<template>
  <div class="panel-body">
    <ui-loadding v-if="loadding"></ui-loadding>
    <div v-else class="form-horizontal">
      
      <!-- 选择驱动 -->
      <module-cdn-select :handle-select="handleSelect" value="filesystem"></module-cdn-select>

      <!-- 磁盘选择 -->
      <module-cdn-filesystem-disk :disk="disk" @change="handleSelectDisk"></module-cdn-filesystem-disk>

      <!-- 磁盘 -->
      <module-cdn-filesystem-local v-if="disk === 'local'"></module-cdn-filesystem-local>
      <module-cdn-filesystem-public v-else-if="disk === 'public'"></module-cdn-filesystem-public>

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
    loadding: false,
  }),
  methods: {
    handleSelectDisk (disk) {
      this.disk = disk;
    },
  },
  created () {
    this.loadding = true;
    request.get(createRequestURI('cdn/filesystem/disk'), {
      validateStatus: status => status === 200,
    }).then(({ data: { disk = 'public' } }) => {
      this.disk = disk;
      this.loadding = false;
    }).catch(({ response: { data = { message: '加载失败，请刷新重试！' } } = {} }) => {
      this.loadding = false;
      this.$store.dispatch('alert-open', { type: 'danger', message: data });
    });
  }
};
</script>
