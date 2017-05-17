<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">短信发送驱动设置</div>
      <!-- Body -->
      <div class="form-horizontal panel-body">
        <!-- 驱动设置表单 -->
        <div class="form-group">
          <label for="dirver" class="col-sm-2 control-label">驱动</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" id="dirver" placeholder="选择驱动" aria-describedby="dirver-help-block">
          </div>
          <div class="col-sm-6">
            <span id="dirver-help-block" class="help-block">请选择用于发送短信的驱动程序。</span>
          </div>
        </div>
        <!-- 提交表单按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button type="button" class="btn btn-primary">提交</button>
          </div>
          <div class="col-sm-6 help-block">
            <span class="text-success">Test tip</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
