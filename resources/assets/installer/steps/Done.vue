<template>
  <div>
    <el-alert
        :closable="false"
        show-icon
        title=""
        type="success"
      >
        安装完成！
      </el-alert>
      <p>管理员账号：root</p>
      <p>管理员密码：root</p>
  </div>
</template>

<script>
export default {
  name: 'step-done',
};
</script>
