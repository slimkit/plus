<template>
    <div class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default">

      <!-- Title -->
      <div class="panel-heading">原生支付设置[微信支付/支付宝]</div>

      <!-- Loading -->
      <div v-if="load.status === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- Body -->
      <div v-else-if="load.status === 1" class="panel-body form-horizontal">
        <blockquote>
          <p>ThinkSNS+增加使用微信支付/支付宝支付</p>
          <footer>服务器必须安装 OpenSSL 的 PHP 拓展。</footer>
          <footer>微信支付通知地址为 <code>/api/v2/wechatPay/notify</code></footer>
          <footer>支付宝通知地址为 <code>/api/v2/alipay/notify</code></footer>
        </blockquote>

        <!-- APP ID -->
        <div class="form-group">
          <label class="col-sm-2 control-label">应用 ID</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="输入应用 ID">
          </div>
          <span class="col-sm-6 help-block">
            请输入应用ID。
          </span>
        </div>

        <!-- Secret Key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Secret Key</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="请输入 Secret Key">
          </div>
          <span class="col-sm-6 help-block">
            输入 Secret Key，非上线环境请输入 Test Secret Key，正式环境请输入 Live Secret Key。
          </span>
        </div>

        <!-- Ping++ public key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">Ping++ 公钥</label>
          <div class="col-sm-4">
            <textarea class="form-control" rows="3"></textarea>
          </div>
          <span class="col-sm-6 help-block">
            用于 Webhooks 回调时验证其正确性，不设置或者错误设置会造成所有异步通知的订单用户支付成功，但是不会到账。
          </span>
        </div>

        <!-- local private key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">商户私钥</label>
          <div class="col-sm-4">
            <textarea class="form-control" rows="3" ></textarea>
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
            <button v-else type="button" class="btn btn-primary" @click="storeSetting">提交</button>
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
        <button type="button" class="btn btn-primary" @click="storeSetting">重试</button>
      </div>

    </div>
  </div>
</template>

<script>
import lodash from "lodash";
import request, { createRequestURI } from "../../util/request";

export default {
  name: "NewPaySetting",
  data: () => ({
    load: {
      message: "",
      status: 0
    },
    alert: {
      status: false,
      type: "info",
      message: "",
      interval: null
    },
    updating: false
  }),
  methods: {
    getSetting() {
      request
        .get(createRequestURI("wallet/newPaySetting"), {
          validateStatus: status => status === 200
        })
        .then(({ data }) => {
          console.log(data);
          this.load.status = 1;
        });
    },
    storeSetting() {
      const params = {};
      request
        .post(createRequestURI("wallet/newPaySetting"), {
          params,
          validateStatus: status => status === 201
        })
        .then(({ data }) => {
          console.log(data);
        });
    }
  },
  computed: {},
  created() {
    this.getSetting();
  }
};
</script>

<style scoped>
</style>
