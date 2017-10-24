<template>
  <div class="form-group">
    
    <label class="col-sm-2 control-label">CDN</label>
    <div class="col-sm-6">
      <select :value="value" class="form-control" @input="handle">
        <option value="local">本地</option>
        <option value="qiniu">七牛</option>
      </select>
    </div>
    <span class="col-sm-4 help-block">选择你的 CDN 平台</span>

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
