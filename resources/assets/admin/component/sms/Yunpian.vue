<template>
  <div class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">云片 - 驱动配置</div>
      <!-- Loading -->
      <div v-if="loadding.state === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>
      <!-- Body -->
      <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
        <!-- App key -->
        <div class="form-group">
          <label for="app-key" class="col-sm-2 control-label">Api Key</label>
          <div class="col-sm-4">
            <input type="text" name="api_key" class="form-control" id="app-key" placeholder="请输入应用 Api Key" aria-describedby="app-key-help" v-model="options.api_key">
          </div>
          <div class="col-sm-6">
            <span id="app-key-help" class="help-block">输入应用 Api Key 信息</span>
          </div>
        </div>
        <!-- button -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button v-if="submit.state === true" class="btn btn-primary" type="submit" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              提交...
            </button>
            <button v-else type="button" class="btn btn-primary" @click.stop.prevent="submitHandle">提交</button>
          </div>
          <div class="col-sm-6 help-block">
            <span :class="`text-${submit.type}`">{{ submit.message }}</span>
          </div>
        </div>
      </div>
      <!-- Loading Error -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ loadding.message }}</div>
        <button type="button" class="btn btn-primary" @click.stop.prevent="request">刷新</button>
      </div>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const YunpianComponent = {
  data: () => ({
    loadding: {
      state: 0,
      message: '',
    },
    submit: {
      state: false,
      type: 'muted',
      message: '',
    },
    options: {},
  }),
  methods: {
    request() {
      this.loadding.state = 0;
      request.get(
        createRequestURI('sms/driver/yunpian'),
        { validateStatus: status => status === 200 }
      ).then(({ data = {} }) => {
        this.loadding.state = 1;
        this.options = data;
      }).catch(({ response: { data: { message: [ message = '加载失败' ] = [] } = {} } = {} }) => {
        this.loadding.state = 2;
        this.loadding.message = message;
      });
    },
    submitHandle() {
      const { api_key = null } = this.options;
      this.submit.state = true;
      request.patch(
        createRequestURI('sms/driver/yunpian'),
        { api_key },
        { validateStatus: status => status === 201 }
      ).then(({ data: { message: [ message = '提交成功' ] = [] } }) => {
        this.submit.state = false;
        this.submit.type = 'success';
        this.submit.message = message;
      }).catch(({ response: { data: { message: [ message = '提交失败' ] = [] } = {} } = {} }) => {
        this.submit.state = false;
        this.submit.type = 'danger';
        this.submit.message = message;
      });
    }
  },
  created() {
    window.setTimeout(() => this.request(), 500);
  }
};

export default YunpianComponent;
</script>
