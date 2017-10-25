<template>
  <ui-loadding v-if="loadding"></ui-loadding>
  <div v-else>
    
    <!-- key -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Key</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="请输入 AWS Key" v-model="key">
      </div>
      <span class="col-sm-6 help-block">请输入 AWS key.</span>
    </div>

    <!-- secret -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Secret</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="请输入 AWS Secret" v-model="secret">
      </div>
      <span class="col-sm-6 help-block">
        请输入 AWS Secret.
      </span>
    </div>

    <!-- region -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Region</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="请输入 AWS S3 Region" v-model="region">
      </div>
      <span class="col-sm-6 help-block">
        请输入 AWS S3 Region.
      </span>
    </div>

    <!-- bucket -->
    <div class="form-group">
      <label class="col-sm-2 control-label">Bucket</label>
      <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="请输入 AWS S3 Bucket" v-model="bucket">
      </div>
      <span class="col-sm-6 help-block">
        请输入 AWS S3 Bucket.
      </span>
    </div>

    <!-- 提交按钮 -->
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <ui-button type="button" class="btn btn-primary" @click="handleSubmit"></ui-button>
      </div>
    </div>

    <div class="help-block">
      在使用 <a href="https://aws.amazon.com" target="_blank">AWS S3</a> 之前，你需要使用 Composer 安装相应的支持软件包：
      <code>league/flysystem-aws-s3-v3 ~1.0</code>
    </div>

  </div>
</template>

<script>
export default {
  name: 'module-cdn-filesystem-s3',
  data: () => ({
    loadding: false,
    key: null,
    secret: null,
    region: null,
    bucket: null,
  }),
  methods: {
    handleSubmit () {
      console.log(1);
    }
  }
};
</script>
