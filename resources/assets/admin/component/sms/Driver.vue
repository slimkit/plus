<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">短信发送驱动设置</div>
      <!-- Loading -->
      <div v-if="loadding" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>
      <!-- Loading Error. -->
      <div v-else-if="loaddingError" class="panel-body">
        <div class="alert alert-danger" role="alert">{{ loaddingErrorMessage }}</div>
        <button type="button" class="btn btn-primary" @click.stop.prevent="request">刷新</button>
      </div>
      <!-- Body -->
      <div v-else class="form-horizontal panel-body">
        <!-- 驱动设置表单 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">驱动</label>
          <div class="col-sm-4">
            <div class="radio" v-for="name, value in driver" :key="value">
              <label>
                <input type="radio" name="default" :value="value" v-model="selected">
                {{ name }}
              </label>
            </div>
          </div>
          <div class="col-sm-6">
            <span class="help-block">请选择用于发送短信的驱动程序。</span>
          </div>
        </div>
        <!-- 提交表单按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button v-if="submit.loadding === true" class="btn btn-primary" type="submit" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              提交...
            </button>
            <button v-else type="button" class="btn btn-primary" @click.stop.prevent="submitHandle">提交</button>
          </div>
          <div class="col-sm-6 help-block">
            <span :class="`text-${submit.messageType}`">{{ submit.message }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const DriverComponent = {
  data: () => ({
    selected: null,
    driver: [],
    loadding: true,
    loaddingError: false,
    loaddingErrorMessage: '',
    submit: {
      loadding: false,
      message: '',
      messageType: 'muted'
    }
  }),
  methods: {
    request() {
      this.loadding = true;
      request.get(createRequestURI('sms/driver'), { validateStatus: status => status === 200 })
        .then(({ data = {} }) => {
          const { default: selected = null, driver = [] } = data;
          this.loadding = false;
          this.loaddingError = false;
          this.selected = selected;
          this.driver = driver;
        })
        .catch(({ response: { data: { message: [message = '加载驱动设置失败，请刷新重新尝试！'] = [] } = {} } = {} }) => {
          this.loadding = false;
          this.loaddingError = true;
          this.loaddingErrorMessage = message;
        });
    },
    submitHandle() {
      const selected = this.selected;
      this.submit.loadding = true;
      this.submit.message = '';
      request.patch(
        createRequestURI('sms/driver'),
        { default: selected },
        { validateStatus: status => status === 201 }
      ).then(({ data: { message: [message = '更新成功'] = [] } = {} }) => {
        this.submit.loadding = false;
        this.submit.message = message;
        this.submit.messageType = 'success';
        window.setTimeout(() => {
          this.submit.message = '';
        }, 3000);
      }).catch(({ response: { data: { message: [message = '更新失败'] = [] } = {} } = {} }) => {
        this.submit.loadding = false;
        this.submit.message = message;
        this.submit.messageType = 'danger';
      });
    }
  },
  created() {
    window.setTimeout(() => this.request(), 500);
  }
};

export default DriverComponent;
</script>
