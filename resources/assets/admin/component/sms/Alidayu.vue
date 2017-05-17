<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">阿里大于 - 驱动配置</div>
      <!-- Loading -->
      <div v-if="loadding.state === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>
      <!-- Body -->
      <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
        <!-- App key -->
        <div class="form-group">
          <label for="app-key" class="col-sm-2 control-label">App Key</label>
          <div class="col-sm-4">
            <input type="text" name="app_key" class="form-control" id="app-key" placeholder="请输入应用 AppKey" aria-describedby="app-key-help">
          </div>
          <div class="col-sm-6">
            <span id="app-key-help" class="help-block">输入应用 App Key 信息</span>
          </div>
        </div>
        <!-- App Secret -->
        <div class="form-group">
          <label for="app-secret" class="col-sm-2 control-label">App Secret</label>
          <div class="col-sm-4">
            <input type="text" name="app_secret" class="form-control" id="app-secret" placeholder="请输入应用 App Secret" aria-describedby="app-secret-help">
          </div>
          <div class="col-sm-6">
            <span id="app-secret-help" class="help-block">输入应用 App Secret 信息</span>
          </div>
        </div>
        <!-- 短信签名 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="sign-name">短信签名</label>
          <div class="col-sm-4">
            <input type="text" name="sign_name" class="form-control" id="sign-name" placeholder="请输入短信签名名称" aria-describedby="sign-name-help">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="sign-name-help">请输入短信签名的名称</span>
          </div>
        </div>
        <!-- 短信模板 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="template-id">模板ID</label>
          <div class="col-sm-4">
            <input type="text" name="template_id" class="form-control" id="template-id" placeholder="请输入短信模板id" aria-describedby="template-id-help">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="template-id-help">请输入短信模板id</span>
          </div>
        </div>
        <!-- button -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button type="button" class="btn btn-primary">提交</button>
          </div>
          <div class="col-sm-6 help-block">
            <span class="text-">哈哈</span>
          </div>
        </div>
      </div>
      <!-- Loading Error -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ loadding.message }}</div>
        <button type="button" class="btn btn-primary">刷新</button>
      </div>
    </div>
  </div>
</template>

<script>
const AlidayuComponent = {
  data: () => ({
    loadding: {
      state: 2,
      message: '',
    },
  })
};

export default AlidayuComponent;
</script>
