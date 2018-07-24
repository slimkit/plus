<template>
  <div class="panel-body">
    <ui-loading v-if="loading"></ui-loading>
    <div v-else class="form-horizontal">

      <!-- selece cdn -->
      <module-cdn-select :handle-select="handleSelect" value="alioss"></module-cdn-select>
  
      <!-- 存储空间 -->
      <div class="form-group">
        <label class="col-sm-2 control-label">存储空间</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="请输入bucket" v-model="bucket">
        </div>
        <span class="col-sm-6 help-block">在「阿里云-对象存储」创建的存储空间(bucket)</span>
      </div>

      <!-- 访问空间 -->
      <div class="form-group">
        <label class="col-sm-2 control-label">访问域名</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="请输入访问域名" v-model="endpoint">
        </div>
        <span class="col-sm-6 help-block">设置「阿里云-对象存储」的存储空间对应的访问域名(endpoint)。</span>
      </div>

      <!-- 是否有cdn加速域名 -->
      <div class="form-group">
        <label class="col-sm-2 control-label">是否cdn加速</label>
        <div class="col-sm-4 radio">
          <label for="one">
            <input type="radio" id="one" :value="true" v-model="isCname">
            已加速
          </label>
          <label for="two">
            <input type="radio" id="two" :value="false" v-model="isCname">
            未加速
          </label>
        </div>
        <span class="col-sm-6 help-block">如果此处设置已加速, 访问域名请设置为绑定的cdn加速域名</span>
      </div>

      <!-- Acces Key -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Access Key</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="请输入 AccessKeyId." v-model="AccessKeyId">
        </div>
        <span class="col-sm-6 help-block">请输入 Access Key, 如果存储空间设置为私有读则必须设置。</span>
      </div>

      <!-- Secret key -->
      <div class="form-group">
        <label class="col-sm-2 control-label">Secret Key</label>
        <div class="col-sm-4">
          <input type="text" class="form-control" placeholder="请输入 AccessKeySecret." v-model="AccessKeySecret">
        </div>
        <span class="col-sm-6 help-block">请输入 Secret Key，如果存储空间设置为私有读则必须设置。</span>
      </div>

      <!-- ssl -->
      <div class="form-group">
        <label class="col-sm-2 control-label">ssl</label>
        <div class="col-sm-4">
          <select class="form-control" v-model="ssl">
            <option :value="false">关闭</option>
            <option :value="true">开启</option>
          </select>
        </div>
      </div>

      <!-- public -->
      <div class="form-group">
        <label class="col-sm-2 control-label">权限</label>
        <div class="col-sm-4">
          <select class="form-control" v-model="isPublic">
            <option :value="false">私有</option>
            <option :value="true">公开</option>
          </select>
        </div>
      </div>

      <!-- expires -->
      <div class="form-group">
        <label class="col-sm-2 control-label">授权期限</label>
        <div class="col-sm-4">
          <input type="number" class="form-control" placeholder="请输入有效期" min="0" v-model.number="expires" :disabled="isPublic">
        </div>
        <span class="col-sm-6 help-block">设置签字授权有效期，单位是「秒」，默认一般为 3600 秒，时长表示单个资源授权访问授权的有效期。</span>
      </div>

      <!-- 提交按钮 -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <ui-button type="button" class="btn btn-primary" @click="handleSubmit"></ui-button>
        </div>
      </div>

    <div class="help-block">
      在使用 <a href="https://www.aliyun.com/product/oss" target="_blank">阿里云 OSS</a> 之前，你需要使用 Composer 安装相应的支持软件包：
      <code>composer require aliyuncs/oss-sdk-php</code>
    </div>

    </div>
  </div>
</template>

<script>
import Select from "./Select";
import request, { createRequestURI } from "../../../util/request";
export default {
  name: "module-cdn-alioss",
  components: {
    [Select.name]: Select
  },
  props: {
    handleSelect: { type: Function, required: true }
  },
  data: () => ({
    loading: false,
    bucket: "",
    endpoint: "",
    AccessKeyId: "",
    AccessKeySecret: "",
    expires: 3600,
    ssl: false,
    isPublic: true,
    isCname: false
  }),
  methods: {
    handleSubmit({ stopProcessing }) {
      const params = {
        bucket: this.bucket,
        endpoint: this.endpoint,
        expires: this.expires,
        AccessKeyId: this.AccessKeyId,
        AccessKeySecret: this.AccessKeySecret,
        ssl: this.ssl,
        isPublic: this.isPublic,
        isCname: this.isCname
      };
      request
        .post(createRequestURI("cdn/alioss"), params, {
          validateStatus: status => status === 201
        })
        .then(({ data }) => {
          this.$store.dispatch("alert-open", {
            type: "success",
            message: data
          });
          stopProcessing();
        })
        .catch(({ response: { data = { message: "设置失败，请重试！" } } }) => {
          this.$store.dispatch("alert-open", { type: "danger", message: data });
          stopProcessing();
        });
    }
  },
  created() {
    this.loading = true;
    request
      .get(createRequestURI("cdn/alioss"), {
        validateStatus: status => status === 200
      })
      .then(
        ({
          data: {
            bucket,
            endpoint,
            expires,
            AccessKeyId,
            AccessKeySecret,
            ssl,
            isPublic,
            isCname
          }
        }) => {
          this.loading = false;
          this.bucket = bucket;
          this.endpoint = endpoint;
          this.ssl = !!ssl;
          this.isPublic = !!isPublic;
          this.AccessKeyId = AccessKeyId;
          this.AccessKeySecret = AccessKeySecret;
          this.isCname = !!isCname;
          this.expires = expires;
        }
      )
      .catch(
        ({ response: { data = { message: "加载失败，请刷新重试！" } } }) => {
          this.loading = false;
          this.$store.dispatch("alert-open", { type: "danger", message: data });
        }
      );
  }
};
</script>
