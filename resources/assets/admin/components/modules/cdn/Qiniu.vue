<template>
  <div class="panel-body">
    <ui-loadding v-if="loadding"></ui-loadding>
    <div v-else class="form-horizontal">

      <!-- selece cdn -->
      <module-cdn-select :handle-select="handleSelect" value="qiniu"></module-cdn-select>

      <!-- 域名 -->
      <div class="form-group">
        <label class="col-sm-2 control-label">域名</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="https://..." v-model="domain">
        </div>
        <span class="col-sm-6 help-block">设置「<a href="://www.qiniu.com" target="_blank">七牛</a>」的 CDN 或者储存空间访问域名，必须携带访问协议。</span>
      </div>

      <!-- 签名 -->
      <div class="form-group">
        <label class="col-sm-2 control-label">签名</label>
        <div class="col-sm-4">
          <select class="form-control" v-model="sign">
            <option :value="false">公开</option>
            <option :value="true">私有</option>
          </select>
        </div>
        <span class="col-sm-6 help-block">设置是否需要签字，公开云储存或者纯 CDN 则选择「公开」私有云储存必须选择「私有」前台资源才能被访问。</span>
      </div>

      <!-- Acces Key -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Access Key</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="请输入 Access Key." v-model="ak" :disabled="! sign">
        </div>
        <span class="col-sm-6 help-block">请输入 Access Key, 如果签字为「私有」则必须设置。</span>
      </div>

      <!-- Secret key -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Secret Key</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="请输入 Secret Key." v-model="sk" :disabled="! sign">
        </div>
        <span class="col-sm-6 help-block">请输入 Secret Key，如果签字为「私有」则必须设置。</span>
      </div>

      <!-- expires -->
      <div class="form-group">
        <label class="col-sm-2 control-label">签字期限</label>
        <div class="col-sm-4">
          <input type="number" class="form-control" placeholder="请输入有效期" min="0" v-model.number="expires" :disabled="! sign">
        </div>
        <span class="col-sm-6 help-block">设置签字授权有效期，单位是「秒」，默认一般为 3600 秒，时长表示单个资源授权访问授权的有效期。</span>
      </div>

      <!-- 提交按钮 -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <ui-button type="button" class="btn btn-primary" @click="handleSubmit"></ui-button>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import Select from './Select';
import request, { createRequestURI } from '../../../util/request';
export default {
  name: 'module-cdn-qiniu',
  components: {
    [Select.name]: Select,
  },
  props: {
    handleSelect: { type: Function, required: true },
  },
  data: () => ({
    loadding: false,
    domain: '',
    sign: false,
    ak: '',
    sk: '',
    expires: 3600,
  }),
  methods: {
    handleSubmit ({ stopProcessing }) {
      const params = {
        domain: this.domain,
        sign: this.sign,
        expires: this.expires,
        ak: this.ak,
        sk: this.sk,
        cdn: 'qiniu',
      };
      request.post(createRequestURI('cdn/qiniu'), params, {
        validateStatus: status => status === 201,
      }).then(({ data }) => {
        this.$store.dispatch('alert-open', { type: 'success', message: data });
        stopProcessing();
      }).catch(({ response: { data = { message: '设置失败，请重试！' } } }) => {
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
        stopProcessing();
      });
    }
  },
  created () {
    this.loadding = true;
    request.get(createRequestURI('cdn/qiniu'), {
      validateStatus: status => status === 200,
    }).then(({ data: { domain, sign, ak, sk, expires } }) => {
      this.loadding = false;
      this.domain = domain;
      this.sign = !! sign;
      this.ak = ak;
      this.sk = sk;
      this.expires = expires;
    }).catch(({ response: { data = { message: '加载失败，请刷新重试！' } } }) => {
      this.loadding = false;
      this.$store.dispatch('alert-open', { type: 'danger', message: data });
    });
  }
};
</script>
