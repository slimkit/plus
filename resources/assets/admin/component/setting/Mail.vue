<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">邮件配置 
		<router-link to="/setting/sendmail">
        	<button type="button" class="btn btn-primary btn-xs pull-right">测试发送</button>
      	</router-link>      
      </div>
            <!-- Loading -->
      <div v-if="loadding.state === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>
      <!-- Body -->
      <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
        <!-- SMTP主机地址 -->
        <div class="form-group">
          <label for="host" class="col-sm-2 control-label">SMTP主机地址</label>
          <div class="col-sm-4">
            <input type="text" name="host" class="form-control" id="host" placeholder="请输入SMTP主机地址" aria-describedby="host-help" v-model="options.host">
          </div>
          <div class="col-sm-6">
            <span id="host-help" class="help-block">输入SMTP主机地址</span>
          </div>
        </div>
        <!-- SMTP主机端口 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="port">SMTP主机端口</label>
          <div class="col-sm-4">
            <input type="text" name="port" class="form-control" id="port" placeholder="请输入SMTP主机端口" aria-describedby="port-help" v-model="options.port">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="port-help">请输入SMTP主机端口</span>
          </div>
        </div>
        <!-- 邮件地址 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="address">邮件地址</label>
          <div class="col-sm-4">
            <input type="text" name="address" class="form-control" id="address" placeholder="请输入发送邮件地址" aria-describedby="address-help" v-model="options.from.address">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="address-help">请输入发送邮件地址</span>
          </div>
        </div>
		<!-- 发送名称 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="name">发送名称</label>
          <div class="col-sm-4">
            <input type="text" name="name" class="form-control" id="name" placeholder="请输入发送名称" aria-describedby="name-help" v-model="options.from.name">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="name-help">请输入发送名称</span>
          </div>
        </div>
		<!-- 传输协议加密方式 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="encryption">传输协议加密方式</label>
          <div class="col-sm-4">
          	<input type="radio" name="encryption" value="tls" v-model="options.encryption">TLS  &nbsp;
            <input type="radio" name="encryption" value="ssl" v-model="options.encryption">SSL	&nbsp;
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="encryption-help">选择邮件传输协议加密方式</span>
          </div>
        </div> 
		<!-- SMTP服务器用户名 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="username">SMTP服务器用户名</label>
          <div class="col-sm-4">
            <input type="text" name="username" class="form-control" id="username" placeholder="请输入SMTP服务器用户名" aria-describedby="username-help" v-model="options.username">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="username-help">请输入SMTP服务器用户名</span>
          </div>
        </div>
		<!-- SMTP服务器密码 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="password">SMTP服务器密码</label>
          <div class="col-sm-4">
            <input type="password" name="password" class="form-control" id="password" placeholder="请输入SMTP服务器密码" aria-describedby="password-help" v-model="options.password">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="password-help">请输入SMTP服务器密码</span>
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

const MailComponent = {
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
        createRequestURI('site/mail'),
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
      this.submit.state = true;
      request.patch(
        createRequestURI('site/mail'),
        this.options,
        { validateStatus: status => status === 201 }
      ).then(({ data: { message = '提交成功' } }) => {
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

export default MailComponent;
</script>
