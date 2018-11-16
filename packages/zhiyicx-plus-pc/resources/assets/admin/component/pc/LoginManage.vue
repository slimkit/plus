<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">三方配置</div>
            <!-- Loading -->
      <div v-if="loadding.state === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>
      <!-- Body -->
      <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="url">微博 app_key</label>
          <div class="col-sm-4">
            <input type="text" name="weibo_app_key" class="form-control" id="weibo_app_key" v-model="site.weibo.client_id">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="position">微博 app_secret</label>
          <div class="col-sm-4">
              <input type="text" name="weibo_app_secret" class="form-control" id="weibo_app_secret" v-model="site.weibo.client_secret">
          </div>
        </div>
		<div class="form-group">
          <label class="col-sm-2 control-label" for="position">微信 app_key</label>
          <div class="col-sm-4">
              <input type="text" name="wechat_app_key" class="form-control" id="wechat_app_key" v-model="site.wechat.client_id">
          </div>
        </div>
		<div class="form-group">
          <label class="col-sm-2 control-label" for="position">微信 app_secret</label>
          <div class="col-sm-4">
              <input type="text" name="wechat_app_secret" class="form-control" id="wechat_app_secret" v-model="site.wechat.client_secret">
          </div>
        </div>
		<div class="form-group">
          <label class="col-sm-2 control-label" for="position">QQ app_key</label>
          <div class="col-sm-4">
              <input type="text" name="qq_app_key" class="form-control" id="qq_app_key" v-model="site.qq.client_id">
          </div>
        </div>
		<div class="form-group">
          <label class="col-sm-2 control-label" for="position">QQ app_secret</label>
          <div class="col-sm-4">
              <input type="text" name="qq_app_secret" class="form-control" id="qq_app_secret" v-model="site.qq.client_secret">
          </div>
        </div>
        <!-- button -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button v-if="submit.state === true" class="btn btn-primary" type="submit" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              提 交...
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

const LoginManageComponent = {
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
    site: {
      weibo: {
        client_id: '',
        client_secret: '',
      },
      wechat: {
        client_id: '',
        client_secret: '',
      },
      qq: {
        client_id: '',
        client_secret: '',
      }
    }
  }),
  methods: {
	requestSiteInfo () {
      request.get(createRequestURI('site/baseinfo'), {
        validateStatus: status => status === 200
      }).then(({ data = {} }) => {
        this.site = data;
        this.loadding.state = 1;
      }).catch(({ response: { data: { message = '加载失败' } = {} } = {} }) => {
        this.loadding.state = 0;
        window.alert(message);
      });
    },
    submitHandle() {
      this.submit.state = true;
      request.patch(
        createRequestURI('site/baseinfo'),
        {site: this.site},
        { validateStatus: status => status === 201 }
      ).then(({ message = '操作成功' }) => {
        this.submit.state = false;
        this.submit.type = 'success';
        this.submit.message = message;
      }).catch(({ response: { message: [ message = '提交失败' ] = [] } = {} }) => {
        this.submit.state = false;
        this.submit.type = 'danger';
        this.submit.message = message;
      });
    }
  },
	created() {
		this.requestSiteInfo();
	}
};

export default LoginManageComponent;
</script>
