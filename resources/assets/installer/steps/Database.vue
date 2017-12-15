<template>
  <el-form ref="form" label-width="0px" label-position="left">

    <el-form-item>
      <el-alert
        :closable="false"
        show-icon
        title=""
        type="info"
      >
        因为数据库执行较为敏感，请在命令行执行 <code>php artisan migrate --seed</code>
        执行完成后，点击下面按钮，完成安装。如果你在执行的时候出现数据库连接错误，可以点击「上一步」按钮返回编辑数据库信息。
      </el-alert>
    </el-form-item>

    <el-form-item>
      <el-button type="primary" @click="handleToPrev">上一步</el-button>
      <el-button type="primary" @click="handleNext">执行完成</el-button>
    </el-form-item>

  </el-form>
</template>
<script>
export default {
  name: 'step-database',
  props: {
    step: { type: Number, required: true },
    handleStep: { type: Function, required: true },
  },
  methods: {
    handleToPrev () {
      this.handleStep(this.step - 1);
    },
    handleNext() {
      this.handleStep(this.step + 1, { done: 'success' });
    }
  }
};
</script>