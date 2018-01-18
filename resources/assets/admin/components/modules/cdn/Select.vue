<template>
  <div class="form-group">
    
    <label class="col-sm-2 control-label">CDN</label>
    <div class="col-sm-4">
      <select :value="value" class="form-control" @input="handle">
        <option value="filesystem">文件系统</option>
        <option value="qiniu">七牛</option>
        <option value="alioss">阿里云 OSS</option>
      </select>
    </div>
    <span class="col-sm-6 help-block">选择你的 CDN 平台，如果选择非文件系统，则所有文件都将储存在「本地公开」磁盘上。</span>

  </div>
</template>

<script>
export default {
  name: 'module-cdn-select',
  props: {
    handleSelect: { type: Function, required: true },
    value: { type: String, required: true },
  },
  methods: {
    handle (event) {
      this.handleSelect(
        event.target.value
      );
    }
  }
};
</script>
