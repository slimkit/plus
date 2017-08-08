<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">测试发送邮件
		    <router-link to="/setting/mail">
        	<button type="button" class="btn btn-primary btn-xs pull-right">返 回</button>
      	</router-link>   	
      </div>
      <!-- Body -->
      <div class="panel-body form-horizontal">
        <!-- 发送地址 -->
        <div class="form-group">
          <label for="email" class="col-sm-2 control-label">邮件地址</label>
          <div class="col-sm-4">
            <input type="text" name="email" class="form-control" id="email" placeholder="请输入邮件地址" v-model="options.email">
          </div>
        </div>
		<!-- 测试邮件内容 -->
        <div class="form-group">
          <label class="col-sm-2 control-label" for="content">邮件内容</label>
          <div class="col-sm-4">
            <input type="text" name="content" class="form-control" id="content" placeholder="请输入发送内容" v-model="options.content">
          </div>
        </div>             
        <!-- button -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button v-if="submit.state === true" class="btn btn-primary" type="submit" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              提交...
            </button>
            <button v-else type="button" class="btn btn-primary" @click.stop.prevent="submitHandle">发送</button>
          </div>
          <div class="col-sm-6 help-block">
            <span :class="`text-${submit.type}`">{{ submit.message }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const AlidayuComponent = {
  data: () => ({
    submit: {
      state: false,
      type: 'muted',
      message: '',
    },
    options: {},
  }),
  methods: {
    submitHandle() {
      this.submit.state = true;
      request.post(
        createRequestURI('site/sendmail'),
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
  }
};

export default AlidayuComponent;
</script>
