<template>
  <div class="form-group">
    <label class="col-sm-2 control-label">磁盘</label>
    <div class="col-sm-4">
      <select class="form-control" :value="disk" @change="handleChange">
        <option value="local">本地</option>
        <option value="public">本地公开</option>
        <option value="s3">Amazon S3</option>
      </select>
    </div>
    <span class="col-sm-6 help-block">选择你需要的文件系统磁盘。</span>
  </div>
</template>

<script>
export default {
  name: 'module-cdn-filesystem-disk',
  props: {
    disk: { type: String, required: true, },
  },
  methods: {
    handleChange (event) {
      this.$emit('change', event.target.value);
    }
  },
};
</script>
