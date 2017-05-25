<style lang="scss" module>
.alert {
  margin: 22px 0 0;
}
</style>

<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- title -->
      <div class="panel-heading">基础设置 - 规则描述设置</div>

      <!-- Loading -->
      <div v-if="load.status === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- Body -->
      <div v-else-if="load.status === 1" class="panel-body form-horizontal">
          
        <!-- 规则表单 -->
        <div class="form-group">
          <label for="wallet-rule" class="col-sm-2 control-label">规则描述</label>
          <div class="col-sm-4">
            <textarea class="form-control" id="wallet-rule" rows="3" aria-describedby="wallet-rule-help" placeholder="输入规则" v-model="rule"></textarea>
          </div>
          <div class="col-sm-6">
            <span id="wallet-rule" class="help-block">输入充值、提现等描述规则。</span>
          </div>
        </div>

        <!-- 提交按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button v-if="update === true" class="btn btn-primary" type="submit" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              提交...
            </button>
            <button v-else type="button" class="btn btn-primary" @click="updateRule">提交</button>
          </div>
        </div>

        <!-- 警告框 -->
        <div v-show="alert.open" :class="['alert', `alert-${alert.type}`, $style.alert]" role="alert">
          {{ alert.message }}
        </div>

      </div>

      <!-- Load error. -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ load.message }}</div>
        <button type="button" class="btn btn-primary" @click="loadRule">刷新</button>
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
      message: null
    },
    rule: null,
    update: false,
    alert: {
      open: false,
      interval: null,
      type: 'info',
      message: null,
    },
  }),
  methods: {
    /**
     * 加载规则.
     *
     * @return {vodi}
     * @author Seven Du <shiweidu@outlook.com>
     */
    loadRule() {
      this.load.status = 0;
      request.get(
        createRequestURI('wallet/rule'),
        { validateStatus: status => status === 200 }
      ).then(({ data: { rule = null } }) => {
        this.load.status = 1;
        this.rule = rule;
      }).catch(({ response: { data: { message = [ message ] = [] } = {} } = {} }) => {
        this.load = {
          status: 2,
          message
        };
      });
    },

    /**
     * 发送更新规则.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateRule() {
      const rule = this.rule;
      this.update = true;
      request.patch(
        createRequestURI('wallet/rule'),
        { rule },
        { validateStatus: status => status === 201 }
      ).then(() => {
        this.update = false;
        this.sendAlert('success', '更新成功！');
      }).catch(({ response: { data: { message: [ message = '提交失败，请重试！' ] = [] } = {} } = {} }) => {
        this.update = false;
        this.sendAlert('danger', message);
      });
    },

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
    }
  },
  created() {
    this.loadRule();
  }
};
</script>
