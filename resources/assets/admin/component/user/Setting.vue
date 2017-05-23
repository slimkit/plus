<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- Title -->
      <div class="panel-heading">设置用户相关基础信息</div>

      <!-- Loading -->
      <div v-if="loadding.state === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- Body -->
      <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
        <!-- 默认用户组 -->
        <div class="form-group">
          <label for="app-key" class="col-sm-2 control-label">默认用户组</label>
          <div class="col-sm-4">
            <select class="form-control" v-model="option.role">
              <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.display_name }}</option>
            </select>
          </div>
          <div class="col-sm-6">
            <span id="app-key-help" class="help-block">选择用户注册的默认用户组</span>
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
import request, { createRequestURI } from '../../util/request';

const UserSettingComponent = {
  data: () => ({
    loadding: {
      state: 0,
      message: '',
    },
    option: {},
    roles: [],
    submit: {
      state: false,
      type: 'muted',
      message: '',
    },
  }),
  methods: {
    request() {
      this.loadding.state = 0;
      request.get(
        createRequestURI('user/setting'),
        { validateStatus: status => status === 200 }
      ).then(({ data = {} }) => {
        const { roles = [], current_role } = data;
        this.roles = roles;
        this.option = {
          ...this.option,
          role: current_role
        };
        this.loadding.state = 1;
      }).catch(({ response: { data: { message: [ message = '加载失败' ] = [] } = {} } = {} }) => {
        this.loadding = {
          state: 2,
          message,
        };
      });
    },
    submitHandle() {
      const { role } = this.option;
      this.submit.state = true;
      request.patch(
        createRequestURI('user/setting'),
        { role },
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

export default UserSettingComponent;
</script>
