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
          <footer><a href="https://pay.weixin.qq.com/wiki/doc/api/index.html">开发文档</a></footer>
        </blockquote>

        <!-- APP ID -->
        <div class="form-group">
          <label class="col-sm-2 control-label">微信公众号APPID</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="输入 微信公众号APPID" v-model="wechatPayAppId">
          </div>
          <span class="col-sm-6 help-block">
            请输入 微信公众号APPID。
          </span>
        </div>

        <!-- Secret Key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">微信公众号APIKEY</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="请输入 微信公众号APIKEY" v-model="wechatPayApiKey">
          </div>
          <span class="col-sm-6 help-block">
            输入 微信公众号APIKEY。
          </span>
        </div>

        <!-- Ping++ public key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">微信公众号MCHID</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="请输入 微信公众号MCHID" v-model="wechatPayMchId">
          </div>
          <span class="col-sm-6 help-block">
            输入 微信公众号MCHID
          </span>
        </div>
        <hr />
        <blockquote>
          <p>支付宝支付设置</p>
          <footer>服务器必须安装 OpenSSL 的 PHP 拓展。</footer>
          <footer>支付宝通知地址为 <code>/api/v2/alipay/notify</code></footer>
          <footer><a href="https://docs.open.alipay.com/">开发中心</a></footer>
        </blockquote>
        <div class="form-group">
          <label class="col-sm-2 control-label">支付宝APPId</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="请输入 微信公众号MCHID" v-model="alipayAppid">
          </div>
          <span class="col-sm-6 help-block">
            输入 支付宝appId
          </span>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">支付宝签名算法</label>
          <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="请输入 支付宝签名算法" v-model="alipaySignType">
          </div>
          <span class="col-sm-6 help-block">
            商户生成签名字符串所使用的签名算法类型，目前支持RSA2和RSA，推荐使用RSA2 <a href="https://docs.open.alipay.com/291/105971/">签名生成教程</a>
          </span>
        </div>
        <!-- local private key -->
        <div class="form-group">
          <label class="col-sm-2 control-label">支付宝应用公钥</label>
          <div class="col-sm-4">
            <textarea placeholder="填写支付宝管理页面设置的应用公钥" class="form-control" rows="4"  v-model="alipayPublicKey"></textarea>
          </div>
          <span class="col-sm-6 help-block">
            填写支付宝管理页面设置的应用公钥
          </span>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">支付宝公钥</label>
          <div class="col-sm-4">
            <textarea placeholder="填写支付宝管理页面设置的支付宝公钥" class="form-control" rows="4"  v-model="alipayAliPayKey"></textarea>
          </div>
          <span class="col-sm-6 help-block">
            填写支付宝管理页面设置的支付宝公钥
          </span>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">支付宝密钥</label>
          <div class="col-sm-4">
            <textarea placeholder="填写支付宝管理页面设置的密钥" class="form-control" rows="4" v-model="alipaySecretKey"></textarea>
          </div>
          <span class="col-sm-6 help-block">
            填写支付宝管理页面设置的密钥
          </span>
        </div>
        <hr>
        <blockquote>
          <p>内部订单识别标识</p>
        </blockquote>
        <div class="form-group">
          <label class="col-sm-2 control-label">支付宝密钥</label>
          <div class="col-sm-4">
            <input placeholder="填写内部订单标识" class="form-control" rows="4" v-model="outTradeNoSign" />
          </div>
          <span class="col-sm-6 help-block">
            填写内部订单标识，默认是时间+4位随机字符，标识填写请参考支付宝以及微信的内部订单号标识为准
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
    config: {
      wechatPay: {
        appId: "",
        apiKey: "",
        mchId: ""
      },
      alipay: {
        appId: "",
        publicKey: "",
        secretKey: "",
        signType: "RSA2",
        alipayAlipayKey: ""
      },
      sign: ""
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
          this.config = { ...data };
          this.load.status = 1;
        });
    },
    storeSetting() {
      const { config: { wechatPay, alipay, sign } } = this;
      const params = { wechatPay, alipay, sign };
      request
        .post(createRequestURI("wallet/newPaySetting"), {
          ...params,
          validateStatus: status => status === 201
        })
        .then(({ data }) => {
          console.log(data);
        });
    }
  },
  computed: {
    wechatPay() {
      const { config: { wechatPay = {} } = {} } = this;
      return wechatPay;
    },
    alipay() {
      const { config: { alipay = {} } = {} } = this;
      return alipay;
    },
    wechatPayAppId: {
      get: function() {
        return this.wechatPay.appId || "";
      },
      set: function(appId) {
        const wechatPay = this.config.wechatPay || {};
        wechatPay.appId = appId;
        this.config = { ...this.config, wechatPay };
      }
    },
    wechatPayMchId: {
      get: function() {
        return this.wechatPay.mchId || "";
      },
      set: function(mchId) {
        const wechatPay = this.config.wechatPay || {};
        wechatPay.mchId = mchId;
        this.config = { ...this.config, wechatPay };
      }
    },
    wechatPayApiKey: {
      get: function() {
        return this.wechatPay.apiKey || "";
      },
      set: function(apiKey) {
        const wechatPay = this.config.wechatPay || {};
        wechatPay.apiKey = apiKey;
        this.config = { ...this.config, wechatPay };
      }
    },
    alipayAppid: {
      get: function() {
        return this.alipay.appId || "";
      },
      set: function(appId) {
        const alipay = this.config.alipay || {};
        alipay.appId = appId;
        this.config = { ...this.config, alipay };
      }
    },
    alipaySignType: {
      get: function() {
        return this.alipay.signType || "";
      },
      set: function(signType) {
        const alipay = this.config.alipay || {};
        alipay.signType = signType;
        this.config = { ...this.config, alipay };
      }
    },
    alipayPublicKey: {
      get: function() {
        return this.alipay.publicKey || "";
      },
      set: function(publicKey) {
        const alipay = this.config.alipay || {};
        alipay.publicKey = publicKey;
        this.config = { ...this.config, alipay };
      }
    },
    alipayAliPayKey: {
      get: function() {
        return this.alipay.alipayKey || "";
      },
      set: function(alipayKey) {
        const alipay = this.config.alipay || {};
        alipay.alipayKey = alipayKey;
        this.config = { ...this.config, alipay };
      }
    },
    alipaySecretKey: {
      get: function() {
        return this.alipay.secretKey || "";
      },
      set: function(secretKey) {
        const alipay = this.config.alipay || {};
        alipay.secretKey = secretKey;
        this.config = { ...this.config, alipay };
      }
    },
    outTradeNoSign: {
      get: function() {
        return this.config.sign || "";
      },
      set: function(sign) {
        this.config.sign = sign;
      }
    }
  },
  created() {
    this.getSetting();
  }
};
</script>
