<template>
  <div class="panel panel-default">

    <!-- title -->
    <div class="panel-heading">跨域设置</div>

    <!-- body -->
    <div class="panel-body">
      
      <!-- loading -->
      <sb-ui-loading v-if="loading" />

      <!-- form -->
      <div class="form-horizontal" v-else>

        <!-- Access-Control-Allow-Credentials -->
        <div class="form-group">
          <label class="col-sm-4 control-label">Access-Control-Allow-Credentials</label>
          <div class="col-sm-4">
            <label class="radio-inline">
              <input type="radio" :value="true" v-model="cors.credentials">&nbsp;true
            </label>
            <label class="radio-inline">
              <input type="radio" :value="false" v-model="cors.credentials">&nbsp;false
            </label>
          </div>
          <div class="col-sm-4 help-block">
            设置参考 <a href="https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Access-Control-Allow-Credentials" target="_blank">Access-Control-Allow-Credentials 技术文档</a>
          </div>
        </div>

        <!-- Access-Control-Allow-Headers -->
        <div class="form-group">
          <label class="col-sm-4 control-label">Access-Control-Allow-Headers</label>
          <div class="col-sm-4">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Header</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="header in cors.allowHeaders" :key="header">
                  <td>{{ header }}</td>
                  <td>
                    <sb-ui-button
                      class="btn btn-danger"
                      @click="event => { handleRemove(event, 'allowHeaders', header) }"
                      label="删除"
                      proces-label="删除中..."
                    ></sb-ui-button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="text" class="form-control" v-model="addHeaderText">
                  </td>
                  <td>
                    <sb-ui-button
                      class="btn btn-primary"
                      @click="event => {handleAppend(event, 'allowHeaders', 'addHeaderText')}"
                      label="添加"
                      proces-label="添加中..."
                    ></sb-ui-button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-sm-4 help-block">
            只要允许中出现 `*` 就忽略成员允许，参考 <a href="https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Access-Control-Allow-Headers">Access-Control-Allow-Headers 技术文档</a>
          </div>
        </div>

        <!-- Access-Control-Expose-Headers -->
        <div class="form-group">
          <label class="col-sm-4 control-label">Access-Control-Expose-Headers</label>
          <div class="col-sm-4">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Header</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="header in cors.exposeHeaders" :key="header">
                  <td>{{ header }}</td>
                  <td>
                    <sb-ui-button
                      class="btn btn-danger"
                      @click="event => { handleRemove(event, 'exposeHeaders', header) }"
                      label="删除"
                      proces-label="删除中..."
                    ></sb-ui-button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="text" class="form-control" v-model="addExposeHeaderText">
                  </td>
                  <td>
                    <sb-ui-button
                      class="btn btn-primary"
                      @click="event => {handleAppend(event, 'exposeHeaders', 'addExposeHeaderText')}"
                      label="添加"
                      proces-label="添加中..."
                    ></sb-ui-button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-sm-4 help-block">
            参考 <a href="https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Access-Control-Expose-Headers">Access-Control-Expose-Headers 技术文档</a>
          </div>
        </div>

        <!-- Access-Control-Allow-Origin -->
        <div class="form-group">
          <label class="col-sm-4 control-label">Access-Control-Allow-Origin</label>
          <div class="col-sm-4">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Origin</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="origin in cors.origins" :key="origin">
                  <td>{{ origin }}</td>
                  <td>
                    <sb-ui-button
                      class="btn btn-danger"
                      @click="event => { handleRemove(event, 'origins', origin) }"
                      label="删除"
                      proces-label="删除中..."
                    />
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="text" class="form-control" v-model="addOriginText">
                  </td>
                  <td>
                    <sb-ui-button
                      class="btn btn-primary"
                      @click="event => {handleAppend(event, 'origins', 'addOriginText')}"
                      label="添加"
                      proces-label="添加中..."
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-sm-4 help-block">
            设置允许的主机，允许的主机里面只要出现 `*` 就代表允许所有主机，不管是否在允许的主机中，例如只允许 *百度* 可以跨域，可以设置为 `https://baidu.com` 更多请参考 <a href="https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Access-Control-Allow-Origin" target="_blank">Access-Control-Allow-Origin 技术文档</a>
          </div>
        </div>

        <!-- Access-Control-Allow-Methods -->
        <div class="form-group">
          <label class="col-sm-4 control-label">Access-Control-Allow-Methods</label>
          <div class="col-sm-4">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Method</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="method in cors.methods" :key="method">
                  <td>{{ method }}</td>
                  <td>
                    <sb-ui-button
                      class="btn btn-danger"
                      @click="event => { handleRemove(event, 'methods', method) }"
                      label="删除"
                      proces-label="删除中..."
                    />
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="text" class="form-control" v-model="addMethodText">
                  </td>
                  <td>
                    <sb-ui-button
                      class="btn btn-primary"
                      @click="event => {handleAppend(event, 'methods', 'addMethodText')}"
                      label="添加"
                      proces-label="添加中..."
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-sm-4 help-block">
            允许跨域请求的方式，请参考 <a href="https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Access-Control-Allow-Methods" target="_blank">Access-Control-Allow-Methods 技术文档</a>
          </div>
        </div>

        <!-- Access-Control-Max-Age -->
        <div class="form-group">
          <label class="col-sm-4 control-label">Access-Control-Max-Age</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" v-model="cors.maxAge" min="0">
          </div>
          <div class="col-sm-4 help-block">
            `Access-Control-Allow-Methods` 和 `Access-Control-Allow-Headers` 提供的信息可以被缓存多久。更多细节请参考 <a href="https://developer.mozilla.org/zh-CN/docs/Web/HTTP/Headers/Access-Control-Max-Age" target="_blank">Access-Control-Max-Age 技术文档</a>
          </div>
        </div>

      </div>
      <!-- End form. -->

      <!-- Submit -->
      <div class="form-group" v-show="!loading">
        <sb-ui-button
          class="btn btn-primary"
          label="提交设置"
          proces-label="提交中..."
          @click="handleSubmit"
        >
        </sb-ui-button>
      </div>

    </div>
    <!-- End body. -->

  </div>
</template>

<script>
import request, { createRequestURI } from '../../../util/request';
export default {
  data: () => ({
    loading: true,
    cors: {
      credentials: false,
      allowHeaders: [],
      exposeHeaders: [],
      origins: [],
      methods: [],
      maxAge: 0,
    },
    addHeaderText: '',
    addExposeHeaderText: '',
    addOriginText: '',
    addMethodText: '',
  }),
  methods: {
    handleSubmit(event) {
      request.put(createRequestURI('settings/cors'), this.cors, {
        validateStatus: status => status === 204,
      }).then(() => {
        this.$store.dispatch('alert-open', { type: 'success', message: '更新成功！' });
      }).catch(({ response: { data: message = '提交错误，请联系技术人员！' } = {} }) => {
        this.$store.dispatch('alert-open', { type: 'danger', message });
      }).finally(() => {
        event.stopProcessing();
      });
    },
    handleRemove(event, listKey, label) {
      const { [listKey]: list } = this.cors;
      let __list = [];
      list.forEach(item => {
        if (item != label) {
          __list.push(item);
        }
      });
      this.cors[listKey] = __list;
      event.stopProcessing();
    },
    handleAppend(event, listKey, tempKey) {
      const text = this[tempKey];
      const { [listKey]: list } = this.cors;

      if (list.indexOf(text) !== -1) {
        event.stopProcessing();
        this.$store.dispatch('alert-open', { type: 'warning', message: '你所添加的元素已存在！' });
        return;
      }

      this.cors[listKey] = [ ...list, text ];
      this[tempKey] = '';
      event.stopProcessing();
    }
  },
  created() {
    request.get(createRequestURI('settings/cors'), {
      validateStatus: status => status === 200,
    }).then(({ data: cors }) => {
      this.cors = cors;
      this.loading = false;
    }).catch(() => {
      this.$store.dispatch('alert-open', { type: 'danger', message: '获取配置失败，请联系技术人员！' });
    });
  }
};
</script>
