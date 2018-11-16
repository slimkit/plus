<template>
  <div class="panel panel-default">
    <!-- Title -->
    <div class="panel-heading">导航菜单配置
      <router-link v-if="options.position == 0" type="button" to="/navmenu">
        <button type="button" class="btn btn-primary btn-xs pull-right">返回</button>
      </router-link>
      <router-link v-else type="button" to="/footnav">
        <button type="button" class="btn btn-primary btn-xs pull-right">返回</button>
      </router-link>
    </div>
          <!-- Loading -->
    <div v-if="loadding.state === 0" class="panel-body text-center">
      <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
      加载中...
    </div>
    <!-- Body -->
    <div v-else-if="loadding.state === 1" class="panel-body form-horizontal">
      <div class="form-group">
        <label class="col-sm-2 control-label" for="name">导航名称</label>
        <div class="col-sm-4">
          <input type="text" name="name" class="form-control" id="name" v-model="options.name">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="app_name">英文名称</label>
        <div class="col-sm-4">
          <input type="text" name="app_name" class="form-control" id="app_name" v-model="options.app_name">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="url">链接地址</label>
        <div class="col-sm-4">
          <input type="text" name="url" class="form-control" id="url" v-model="options.url">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="target">打开方式</label>
        <div class="col-sm-2">
            <select class="form-control" id="target" v-model="options.target">
              <option v-for="(target, index) in targets" :value="index">{{ target }}</option>
            </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="status">状态</label>
        <div class="col-sm-4">
          <input type="radio" name="status" value="0" v-model="options.status">关闭  &nbsp;
          <input type="radio" name="status" value="1" v-model="options.status">开启   &nbsp;
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="position">导航位置</label>
        <div class="col-sm-2">
            <select class="form-control" id="position" v-model="options.position">
              <option v-for="(type, index) in types" :value="index">{{ type }}</option>
            </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="order_sort">导航排序</label>
        <div class="col-sm-3">
          <input type="number" name="order_sort" class="form-control" id="order_sort" v-model="options.order_sort">
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
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const NavmanageComponent = {
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
    targets: {
      _blank: '新窗口',
      _self: '当前窗口',
      _parent: '父窗口'
    },
    types: {
      0: '头部',
      1: '底部'
    },
    options: {
        id: null,
        name: '',
        app_name: '',
        url: '',
        target: '_blank',
        status: 1,
        position: 0,
        parent_id: 0,
        order_sort: 0,
    },
  }),
  methods: {
    getNavById(id) {
      this.loadding.state = 0;
      request.get(
        createRequestURI('nav/get/'+id),
        { validateStatus: status => status === 200 }
      ).then(({ data = {} }) => {
        this.loadding.state = 1;
        this.options = data.data;
      }).catch(({ response: { data: { message: [ message = '获取失败' ] = [] } = {} } = {} }) => {
        this.loadding.state = 2;
        this.loadding.message = message;
      });
    },
    submitHandle() {
      this.submit.state = true;
      request.post(
        createRequestURI('nav/manage'),
        this.options,
        { validateStatus: status => status === 200 }
      ).then(({ message = '操作成功' }) => {
        this.submit.state = false;
        this.submit.type = 'success';
        this.submit.message = message;
        this.$router.push({path:'/navmenu'})
      }).catch(({ response: { message: [ message = '提交失败' ] = [] } = {} }) => {
        this.submit.state = false;
        this.submit.type = 'danger';
        this.submit.message = message;
      });
    }
  },
    created() {

    },
    beforeMount() {
        if (this.$route.params.parentId > 0) {
          this.options.parent_id = this.$route.params.parentId;
        }
        if (this.$route.params.navId > 0) {
            this.getNavById(this.$route.params.navId);
        } else {
            this.loadding.state = 1;
        }
    }
};

export default NavmanageComponent;
</script>
