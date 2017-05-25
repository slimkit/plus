<template>
  <div class="component-container container-fluid">
    <div class="panel panel-default">
      <!-- title -->
      <div class="panel-heading">基础设置 - 转换比例</div>

      <!-- Body -->
      <div class="panel-body form-horizontal">
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

      </div>

    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
export default {
  data: () => ({
    ratio: 100
  }),
  methods: {
    requestRatio() {},
    updateRatio() {}
  }
};
</script>
