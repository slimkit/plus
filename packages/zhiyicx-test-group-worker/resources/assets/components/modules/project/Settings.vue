<template>
  <div>
    <h4>基础设置</h4>
    <el-form label-position="top" :inline="false">
      <el-form-item label="项目名称">
        <el-input type="text" placeholder="请输入项目名称"></el-input>
      </el-form-item>
      <el-form-item label="项目描述">
        <el-input type="textarea" :rows="3" placeholder="请输入项目描述"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary">修改信息</el-button>
      </el-form-item>
    </el-form>

    <h4>移除项目</h4>
    <el-alert
      title="移除这个项目"
      description="移除这个项目，将会删除平台中所有的关联数据，并且再次添加项目的时候旧记录不复存在。"
      type="warning"
      :show-icon="true"
    >
    </el-alert>
    <el-button type="danger" style="margin-top: 24px;">移除项目</el-button>
  </div>
</template>