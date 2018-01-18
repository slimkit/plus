<template>
  <div>
    <el-row type="block" :gutter="24">

      <el-col :span="12">
        <el-card>
          <div slot="header">
            <span>新建一个项目</span>
            <el-button style="float: right; padding: 3px 0" type="text">立即创建</el-button>
          </div>
          <el-form label-position="right" label-width="80px">
            <el-form-item label="项目名称">
              <el-input type="text" placeholder="请输入项目名称"></el-input>
            </el-form-item>
            <el-form-item label="项目描述">
              <el-input type="textarea" :rows="3" placeholder="请输入项目描述"></el-input>
            </el-form-item>
          </el-form>
        </el-card>
      </el-col>

      <el-col :span="12">
        <el-card class="project-card">
          <div slot="header">ThinkSNS Plus 后端</div>

          <el-tooltip content="完成的测试任务" placement="top">
            <el-button type="text" class="project-button" icon="el-icon-circle-check">100</el-button>
          </el-tooltip>
          <el-tooltip content="总测试任务" placement="top">
            <el-button type="text" class="project-button" icon="el-icon-tickets">100</el-button>
          </el-tooltip>
          <el-tooltip content="Issues 数量" placement="top">
            <el-button type="text" class="project-button" icon="el-icon-warning">100</el-button>
          </el-tooltip>

          <div class="project-desc">
            ThinkSNS Plus is the use of Laravel framework to achieve the user ecosystem, Can be friendly and low coupling development development applications.
            <p>
              <el-button >进入项目</el-button>
            </p>
          </div>

        </el-card>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  name: 'page-projects',
};
</script>

<style>
.project-card {
  text-align: center;
  margin-bottom: 20px;
}
.project-button {
  padding: 0;
  font-weight: lighter;
  margin-right: 20px;
  margin-bottom: 20px;
}
.project-button+.project-button {
  margin-right: 20px;
}
.project-desc {
  font-size: 14px;
  color: #99a9bf;
}
</style>
