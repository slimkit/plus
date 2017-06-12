<style lang="scss" module>
.alert {
  margin: 22px 0 0;
}
</style>

<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- title -->
      <div class="panel-heading">
        <router-link to="/wallet/cash" replace>
          <span class="glyphicon glyphicon-menu-left"></span>
          返回
        </router-link>
        <span class="pull-right" >提现审批 - 提现设置</span>
      </div>

      <!-- Loading -->
      <div v-if="load.status === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- Body -->
      <div v-else-if="load.status === 1" class="panel-body form-horizontal">
        <!-- 提现方式 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">提现方式</label>
          <div class="col-sm-4">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="alipay" v-model="cashType"> 支付宝
              </label>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" value="wechat" v-model="cashType"> 微信
              </label>
            </div>
          </div>
          <div class="col-sm-6">
            <span class="help-block">选择用户提现支持的提现方式，如果都不勾选，则表示关闭提现功能。</span>
          </div>
        </div>

        <!-- 最低提现金额 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">最低提现</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" v-model="minAmountCompute">
          </div>
          <span class="col-sm-6 help-block">设置最低用户提现金额，这里设置真实金额，验证的时候会自动验证转换后金额。</span>
        </div>

        <!-- 提交按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button v-if="update === true" class="btn btn-primary" type="submit" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              提交...
            </button>
            <button v-else type="button" class="btn btn-primary" @click="updateHandle">提交</button>
          </div>
        </div>

        <!-- 警告框 -->
        <div v-show="alert.open" :class="['alert', `alert-${alert.type}`, $style.alert]" role="alert">
          {{ alert.message }}
        </div>

      </div>

      <!-- Loading Error. -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ load.message }}</div>
        <button type="button" class="btn btn-primary" @click="requestCashSetting">刷新</button>
      </div>

    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
export default {
  data: () => ({
    alert: {
      open: false,
      interval: null,
      type: 'info',
      message: null,
    },
    cashType: [],
    minAmount: 1,
    load: {
      status: 0,
      message: '',
    },
    update: false,
  }),
  computed: {
    minAmountCompute: {
      set(minAmount) {
        this.minAmount = minAmount * 100;
      },
      get() {
        return this.minAmount / 100;
      }
    }
  },
  methods: {
    /**
     * 发送提示.
     *
     * @param {string} type
     * @param {string} message
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    sendAlert(type, message) {
      window.clearInterval(this.alert.interval);
      this.alert = {
        ...this.alert,
        type,
        message,
        open: true,
        interval: window.setInterval(() => {
          window.clearInterval(this.alert.interval);
          this.alert.open = false;
        }, 2000)
      };
    },

    /**
     * 请求提现方式数据.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestCashSetting() {
      request.get(
        createRequestURI('wallet/cash'),
        { validateStatus: status => status === 200 }
      ).then(({ data: { types = [], min_amount: minAmount = 1 } = {} }) => {
        this.cashType = types;
        this.minAmount = minAmount;
        this.load.status = 1;
      }).catch(({ response: { data: { message: [ message = '加载失败' ] = [] } = {} } = {} } = {}) => {
        this.load = {
          message,
          status: 2
        };
      });
    },

    /**
     * 更新设置.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateHandle() {
      this.update = true;
      request.patch(
        createRequestURI('wallet/cash'),
        { types: this.cashType, min_amount: this.minAmount },
        { validateStatus: status => status === 201 }
      ).then(({ data: { message: [ message = '更新成功' ] = [] } = {} }) => {
        this.update = false;
        this.sendAlert('success', message);
      }).catch(({ response: { data: { message: anyMessage = [], types: typeMessage = [], min_amount: amountMessage = [] } = {} } = {} }) => {
        this.update = false;
        const [ message = '更新失败，请刷新重试' ] = [ ...anyMessage, ...typeMessage, ...amountMessage ];
        this.sendAlert('danger', message);
      });
    },
  },
  created() {
    this.requestCashSetting();
  }
}
</script>
