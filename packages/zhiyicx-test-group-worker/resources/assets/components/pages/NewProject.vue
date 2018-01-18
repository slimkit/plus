<template>
  <el-form ref="form" label-width="120px">
    <!-- 名称 -->
    <el-form-item label="项目名称">
      <el-input type="text" placeholder="请输入项目名称"></el-input>
    </el-form-item>

    <!-- 描述 -->
    <el-form-item label="项目描述">
      <el-input type="textarea" :rows="3" placeholder="请输入项目描述"></el-input>
    </el-form-item>

    <!-- GitHub 地址 -->
    <el-form-item label="GitHub 仓库">
      <el-input placeholder="owner/repo">
        <template slot="prepend">https://github.com/</template>
      </el-input>
    </el-form-item>

    <!-- 资源分支 -->
    <el-form-item label="资源分支">
      <el-input type="text" placeholder="请输入资源分支名称"></el-input>
      <p>
        <el-alert
        title="为什么需要填写资源分支？"
        description="因为使用平台进行 Issus 管理等有文件需求，这个分支专门用于储存静态文件。"
        type="info"
      >
      </el-alert>
      </p>
    </el-form-item>

    <el-form-item>
      <el-button type="primary">创建项目</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
  name: 'page-new-project',
};
</script>
