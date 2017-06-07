<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">

      <!-- Title -->
      <div class="panel-heading">支付设置 - 支付选项</div>

      <!-- Loading -->
      <div v-if="load.status === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- Body -->
      <div v-else-if="load.status === 1" class="panel-body form-horizontal">

        <!-- 提示 -->
        <blockquote>
          <p>如果将充值选项全部取消，则表示关闭充值功能</p>
          <footer>单个平台多个选择时针对不同场景的分类，关闭某个场景，某个场景则无支付。</footer>
        </blockquote>
          
        <!--  选择列表 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">充值选项</label>
          <div class="col-sm-6">
            <div class="checkbox" v-for="name, type in support" :key="type">
              <label>
                <input type="checkbox" :value="type" v-model="types" :disabled="updating"> {{ name }}
              </label>
            </div>
          </div>
          <span class="col-sm-4 help-block">选择不同场景下开启的支付方式，选择后对应场景则无此支付功能。</span>
        </div>

        <!-- 提交按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <div class="col-sm-offset-2 col-sm-10">
            <button v-if="updating" type="button" class="btn btn-primary" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
            </button>
            <button v-else type="button" class="btn btn-primary" @click="updateType">提交</button>
          </div>
          </div>
        </div>

        <!-- 警告框 -->
        <div v-show="alert.status" :class="['alert', `alert-${alert.type}`]" style="margin-top: 16px;" role="alert">
          {{ alert.message }}
        </div>

      </div>

      <!-- Load Error -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ load.message }}</div>
        <button type="button" class="btn btn-primary" @click="requestTypes">重试</button>
      </div>

    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
export default {
  data: () => ({
    load: {
      status: 0,
      message: ''
    },
    support: {},
    types: [],
    updating: false,
    alert: {
      status: false,
      type: 'info',
      message: '',
      interval: null
    },
  }),
  methods: {
    /**
     * Send alert message tip.
     *
     * @param {String} type
     * @param {String} message
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendAlert(type, message) {
      window.clearInterval(this.alert.interval);
      this.alert = { type, message, status: true, interval: window.setInterval(() => {
        window.clearInterval(this.alert.interval);
        this.alert.status = false;
      }, 2000) };
    },

    /**
     * Request support types.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestTypes() {
      this.load.status = 0;
      request.get(
        createRequestURI('wallet/recharge/types'),
        { validateStatus: status => status === 200 }
      ).then(({ data: { support = {}, types = [] } }) => {
        this.load.status = 1;
        this.support = support;
        this.types = types;
      }).catch(({ response: { data: { message: [ message = '加载失败，请刷新页面重试' ] = [] } = {} } = {} } = {}) => {
        this.load = {
          status: 2,
          message
        };
      });
    },

    /**
     * Update support types.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateType() {
      this.updating = true;
      request.patch(
        createRequestURI('wallet/recharge/types'),
        { types: this.types },
        { validateStatus: status => status === 201 }
      ).then(({ data: { message: [ message = '更新成功' ] = [] } }) => {
        this.updating = false;
        this.sendAlert('success', message);
      }).catch(({ response: { data: { types: typeMessage = [], message: anyMessage = [] } = {} } = {} } = {}) => {
        const [ message = '提交失败，请刷新网页重试' ] = [ ...typeMessage, anyMessage ];
        this.updating = false;
        this.sendAlert('danger', message);
      });
    }
  },

  /**
   * The page created.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  created() {
    this.requestTypes();
  }
};
</script>
