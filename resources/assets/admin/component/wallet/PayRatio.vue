<style lang="scss" module>
  .alert {
    margin: 22px 0 0;
  }
</style>

<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- title -->
      <div class="panel-heading">基础设置 - 转换比例</div>

      <!-- Loading -->
      <div v-if="load.status === 0" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>

      <!-- Body -->
      <div v-else-if="load.status === 1" class="panel-body form-horizontal">
        <blockquote>
          <p>转换比例为「真实货币」如人民币，美元等与钱包系统「用户余额」比例的设置。</p>
          <footer>以「CNY」为例，比例设置为 200% 则充值 1CNY 则得到 2 余额。</footer>
        </blockquote>

        <!-- 比例 -->
        <div class="form-group">
          <label for="wallet-ratio" class="col-sm-2 control-label">转换比例</label>
          <div class="col-sm-4">
            <div class="input-group">
              <input type="number" name="ratio" class="form-control" id="wallet-ratio" placeholder="输入转换比例" aria-describedby="wallet-ratio-help" min="1" max="1000" step="1" v-model="ratio">
              <span class="input-group-addon" id="basic-addon2">%</span>
            </div>
          </div>
          <div class="col-sm-6">
            <span id="wallet-ratio-help" class="help-block">输入转换比例，不理只能是正整数，范围在 1 - 1000 之间。</span>
          </div>
        </div>

        <!-- 提交按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <button v-if="update === true" class="btn btn-primary" type="submit" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
              提交...
            </button>
            <button v-else type="button" class="btn btn-primary" @click.stop.prevent="updateRatio">提交</button>
          </div>
        </div>

        <!-- 警告框 -->
        <div v-show="alert.open" :class="['alert', `alert-${alert.type}`, $style.alert]" role="alert">
          {{ alert.message }}
        </div>

      </div>

      <!-- Loading Error -->
      <div v-else class="panel-body">
        <div class="alert alert-danger" role="alert">{{ load.message }}</div>
        <button type="button" class="btn btn-primary" @click="requestRatio">刷新</button>
      </div>

    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
export default {
  data: () => ({
    ratio: 100,
    load: {
      status: 0,
      message: null,
    },
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
     * 请求转换值.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    requestRatio() {
      this.load.status = 0;
      request.get(
        createRequestURI('wallet/ratio'),
        { validateStatus: status => status === 200 }
      ).then(({ data: { ratio } }) => {
        this.load.status = 1;
        this.ratio = ratio;
      }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
        this.load = {
          status: 2,
          message,
        };
      });
    },

    /**
     * 发送转换值到服务端.
     *
     * @return {void}
     * @author Seven Du <shiweidu@outlook.com>
     */
    updateRatio() {
      const ratio = this.ratio;
      this.update = true;
      request.patch(
        createRequestURI('wallet/ratio'),
        { ratio },
        { validateStatus: status => status === 201 }
      ).then(() => {
        this.update = false;
        this.sendAlert('success', '更新成功');
      }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
        this.update = false;
        this.sendAlert('danger', message);
      });
    },
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
  },
  /**
   * 组件创建成功事件.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  created() {
    this.requestRatio();
  }
};
</script>
