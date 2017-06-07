<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">

      <!-- Title -->
      <div class="panel-heading">支付设置 -  Ping++</div>

      <!-- Loading -->
      <div v-if="load.status === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- Body -->
      <div v-else-if="load.status === 1" class="panel-body form-horizontal">
        <blockquote>
          <p>ThinkSNS+ 使用 <a href="https://www.pingxx.com/" target="block">Ping++</a> 进行支付集成，以提供统一的支付接口使其方便拓展。</p>
          <footer>因为使用 RSA 进行认证，所以请服务器安装 OpenSSL 的 PHP 拓展。</footer>
        </blockquote>

        <!-- APP ID -->
        <div class="form-group">
          <label class="col-sm-2 control-label">应用 ID</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="输入应用 ID" v-model="appId">
          </div>
          <span class="col-sm-6 help-block">
            请输入应用ID。
          </span>
        </div>

        <!-- Secret Key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Secret Key</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="请输入 Secret Key" v-model="secretKey">
          </div>
          <span class="col-sm-6 help-block">
            输入 Secret Key，非上线环境请输入 Test Secret Key，正式环境请输入 Live Secret Key。
          </span>
        </div>

        <!-- Ping++ public key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Ping++ 公钥</label>
          <div class="col-sm-4">
            <textarea class="form-control" rows="3" v-model="publicKey"></textarea>
          </div>
          <span class="col-sm-6 help-block">
            用于 Webhooks 回调时验证其正确性，不设置或者错误设置会造成所有异步通知的订单用户支付成功，但是不会到账。
          </span>
        </div>

        <!-- local private key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">商户私钥</label>
          <div class="col-sm-4">
            <textarea class="form-control" rows="3" v-model="privateKey"></textarea>
          </div>
          <span class="col-sm-6 help-block">
            商户私钥是与 Ping++ 服务器交互的认证凭据，可以「<a href="">点击这里</a>」获取一对 公／私钥，获取后倾妥善保管，公钥设置到 Ping++ 中，私钥设置在这里。
          </span>
        </div>

        <!-- 提交按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button v-if="updating" type="button" class="btn btn-primary" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
            </button>
            <button v-else type="button" class="btn btn-primary" @click="updateConfig">提交</button>
          </div>
        </div>

        <!-- 警告框 -->
        <div v-show="alert.status" :class="['alert', `alert-${alert.type}`]" style="margin-top: 16px;" role="alert">
          {{ alert.message }}
        </div>

      </div>

      <!-- Load Error -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ load.message }}</div>
        <button type="button" class="btn btn-primary" @click="requestConfig">重试</button>
      </div>

    </div>
  </div>
</template>
  
<script>
import request, { createRequestURI } from '../../util/request';
export default {
  data: () => ({
    appId: null,
    secretKey: null,
    publicKey: null,
    privateKey: null,
    load: {
      status: 0,
      message: ''
    },
    alert: {
      status: false,
      type: 'info',
      message: '',
      interval: null
    },
    updating: false
  }),
  methods: {
    /**
     * Request Ping++ config.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestConfig() {
      this.load.status = 0;
      request.get(
        createRequestURI('wallet/pingpp'),
        { validateStatus: status => status === 200 }
      ).then(({ data: { app_id: appId, secret_key: secretKey, public_key: publicKey, private_key: privateKey } = {} }) => {
        this.load.status = 1;
        this.appId = appId;
        this.secretKey = secretKey;
        this.publicKey = publicKey;
        this.privateKey = privateKey;
      }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} } = {}) => {
        this.load = {
          status: 2,
          message
        };
      });
    },

    /**
     * Update Ping++ config.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateConfig() {
      this.updating = true;
      request.patch(
        createRequestURI('wallet/pingpp'),
        { app_id: this.appId, secret_key: this.secretKey, public_key: this.publicKey, private_key: this.privateKey },
        { validateStatus: status => status === 201 }
      ).then(({ data: { message: [ message = '更新成功' ] = [] } }) => {
        this.updating = false;
        this.sendAlert('success', message);
      }).catch(({ response: { data: { app_id: appIdMessage = [], secret_key: secretKeyMessage = [], public_key: publicKeyMessage = [], private_key: privateKeyMessage = [], message: anyMessage = [] } = {} } = {} } = {}) => {

        const [ message = '提交失败，请刷新重试！' ] = [ ...appIdMessage, ...secretKeyMessage, ...publicKeyMessage, ...privateKeyMessage, ...anyMessage ];
        this.updating = false;
        this.sendAlert('danger', message);
      });
    },

    /**
     * Send alert message tip.
     *
     * @param {String} type
     * @param {String} message
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendAlert(type, message) {
      window.clearInterval(this.alert.interval);
      this.alert = { type, message, status: true, interval: window.setInterval(() => {
        window.clearInterval(this.alert.interval);
        this.alert.status = false;
      }, 1500) };
    }
  },
  /**
   * The page created.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  created() {
    this.requestConfig();
  }
};
</script>
