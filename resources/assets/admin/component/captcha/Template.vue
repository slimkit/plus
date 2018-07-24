<template>
  <div class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">短信 - 模版配置</div>
      <!-- Loading -->
      <div v-if="loadding.state === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>
      <!-- Body -->
      <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
        <!-- 阿里大于短信模板 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="template-id">阿里大于</label>
          <div class="col-sm-4">
            <input type="text" name="template_id" class="form-control" id="template-id" placeholder="请输入短信模板id" aria-describedby="template-id-help" v-model="options.alidayu_template_id">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="template-id-help">请输入短信模板id</span>
          </div>
        </div>
        <!-- 阿里云短信模板 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="template-id">阿里云</label>
          <div class="col-sm-4">
            <input type="text" name="template_id" class="form-control" id="template-id" placeholder="请输入短信模板id" aria-describedby="template-id-help" v-model="options.aliyun_template_id">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="template-id-help">请输入短信模板id</span>
          </div>
        </div>
        <!-- 云片短信模板 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="template-id">云片</label>
          <div class="col-sm-4">
            <input type="text" name="template_id" class="form-control" id="template-id" placeholder="请输入短信模板" aria-describedby="template-id-help" v-model="options.yunpian_template_content">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="template-id-help">输入应用 content 信息,例:你的短信验证是：:code，注:code为变量</span>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="template-id">互亿无线</label>
          <div class="col-sm-4">
            <input type="text" name="template_id" class="form-control" id="template-id" placeholder="请输入短信模板" aria-describedby="template-id-help" v-model="options.huyi_template_content">
          </div>
          <div class="col-sm-6">
            <span class="help-block" id="template-id-help">输入应用 content 信息,例:你的短信验证是：:code，注:code为变量</span>
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
import request, { createRequestURI } from "../../util/request";

const TemplateComponent = {
  data: () => ({
    loadding: {
      state: 0,
      message: ""
    },
    submit: {
      state: false,
      type: "muted",
      message: ""
    },
    options: {}
  }),
  methods: {
    request() {
      this.loadding.state = 0;
      request
        .get(createRequestURI("sms/templates"), {
          validateStatus: status => status === 200
        })
        .then(({ data = {} }) => {
          this.loadding.state = 1;
          this.options = data;
        })
        .catch(
          ({
            response: {
              data: { message: [message = "加载失败"] = [] } = {}
            } = {}
          }) => {
            this.loadding.state = 2;
            this.loadding.message = message;
          }
        );
    },
    submitHandle() {
      const {
        alidayu_template_id = null,
        aliyun_template_id = null,
        yunpian_template_content = null,
        huyi_template_content = null
      } = this.options;
      console.log(this.options);
      this.submit.state = true;
      request
        .patch(
          createRequestURI("sms/update/templates"),
          {
            alidayu_template_id,
            aliyun_template_id,
            yunpian_template_content,
            huyi_template_content
          },
          { validateStatus: status => status === 201 }
        )
        .then(({ data: { message: [message = "提交成功"] = [] } }) => {
          this.submit.state = false;
          this.submit.type = "success";
          this.submit.message = message;
        })
        .catch(
          ({
            response: {
              data: { message: [message = "提交失败"] = [] } = {}
            } = {}
          }) => {
            this.submit.state = false;
            this.submit.type = "danger";
            this.submit.message = message;
          }
        );
    }
  },
  created() {
    window.setTimeout(() => this.request(), 500);
  }
};

export default TemplateComponent;
</script>
