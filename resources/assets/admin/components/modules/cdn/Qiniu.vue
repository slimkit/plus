<template>
  <div class="panel-body">
    <ui-loading v-if="loading"></ui-loading>
    <div v-else class="form-horizontal">

      <!-- selece cdn -->
      <module-cdn-select :handle-select="handleSelect" value="qiniu"></module-cdn-select>

      <!-- 域名 -->
      <div class="form-group">
        <label class="col-sm-2 control-label">域名</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="https://..." v-model="domain">
        </div>
        <span class="col-sm-6 help-block">设置「<a href="//www.qiniu.com" target="_blank">七牛</a>」的 CDN 或者储存空间访问域名，必须携带访问协议。</span>
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
        <span class="col-sm-6 help-block">设置资源地址是否需要签名。</span>
      </div>

      <!-- Acces Key -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Access Key</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="请输入 Access Key." v-model="ak">
        </div>
        <span class="col-sm-6 help-block">请输入 Access Key, 公开情况也需要设置，刷新缓存等需要用到。</span>
      </div>

      <!-- Secret key -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Secret Key</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="请输入 Secret Key." v-model="sk">
        </div>
        <span class="col-sm-6 help-block">请输入 Secret Key，公开情况也需要设置，刷新缓存等需要用到。</span>
      </div>

      <!-- Type -->
      <div class="form-group">
        <label class="col-sm-2 control-label">类型</label>
        <div class="col-sm-4">
          <select class="form-control" v-model="type">
            <option value="object">对象存储</option>
            <option value="cdn">融合 CDN</option>
          </select>
        </div>
        <span class="col-sm-6 help-block">选择七牛平台使用类型。</span>
      </div>

      <!-- bucket -->
      <div v-show="type === 'object'" class="form-group">
        <label class="col-sm-2 control-label">Bucket</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" v-model="bucket">
        </div>
        <span class="col-sm-6 help-block">输入对象存储空间的 Bucket 。</span>
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

      <div class="col-sm-12 help-block">
        如果设置「融合 CDN」请联系七牛客服开通刷新目录权限，否则头像类缓存，可能刷新失败。
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
    loading: false,
    domain: '',
    sign: false,
    ak: '',
    sk: '',
    expires: 3600,
    type: 'object',
    bucket: null,
  }),
  methods: {
    handleSubmit ({ stopProcessing }) {
      const params = {
        domain: this.domain,
        sign: this.sign,
        expires: this.expires,
        ak: this.ak,
        sk: this.sk,
        type: this.type,
        bucket: this.bucket,
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
    this.loading = true;
    request.get(createRequestURI('cdn/qiniu'), {
      validateStatus: status => status === 200,
    }).then(({ data: { domain, sign, ak, sk, expires, type, bucket } }) => {
      this.loading = false;
      this.domain = domain;
      this.sign = !! sign;
      this.ak = ak;
      this.sk = sk;
      this.expires = expires;
      this.type = type;
      this.bucket = bucket;
    }).catch(({ response: { data = { message: '加载失败，请刷新重试！' } } }) => {
      this.loading = false;
      this.$store.dispatch('alert-open', { type: 'danger', message: data });
    });
  }
};
</script>
