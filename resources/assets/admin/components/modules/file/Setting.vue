<template>
  <div class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">附件设置</div>

      <!-- Loading -->
      <div v-if="loadding.state === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- Body -->
      <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
        <!-- 上传限制 -->
        <div class="form-group">
          <label for="app-key" class="col-sm-2 control-label">上传限制</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" placeholder="限制大小" v-model="maxSize">
          </div>
          <div class="col-sm-6">
            <span id="app-key-help" class="help-block">上传附件的大小限制,单位k</span>
          </div>
        </div>

        <!-- 提交按钮 -->
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
import request, { createRequestURI } from '../../../util/request';

const FileSettingComponent = {
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
    maxSize: 0
  }),
  methods: {
    request() {
      this.loadding.state = 0;
      request.get(
        createRequestURI('files/setting'),
        { validateStatus: status => status === 200 }
      ).then(({ data: { max_size = 0 } = {} }) => {
        this.maxSize = max_size;
        this.loadding.state = 1
      }).catch(({ response: { data: { message: [ message = '加载失败' ] = [] } = {} } = {} }) => {
        this.loadding = {
          state: 2,
          message,
        };
      });
    },
    submitHandle() {
      const max_size = this.maxSize;
      this.submit.state = true;
      request.patch(
        createRequestURI('files/setting'),
        { 'max_size': max_size },
        { validateStatus: status => status === 201 }
      ).then(({ data = {} }) => {
        const { message: [ message ] = [] } = data;
        this.submit = {
          state: false,
          type: 'success',
          message,
        }
        window.setTimeout(() => {
          this.submit.message = '';
        }, 1500);
      }).catch(({ response: { data: { message = [], role = [] } = {} } = {} }) => {
        const [currentMessage = '提交失败'] = [ ...role, ...message ];
        this.submit = {
          state: false,
          type: danger,
          message: currentMessage,
        };
      });
    }
  },
  created() {
    this.request();
  }
};

export default FileSettingComponent;
</script>
