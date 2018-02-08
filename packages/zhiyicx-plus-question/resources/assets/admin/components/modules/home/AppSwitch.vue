<template>
  <div class="panel panel-default">

    <!-- title -->
    <div class="panel-heading">应用设置</div>

    <!-- body -->
    <div class="panel-body">
      <form class="form-horizontal" v-on:submit.prevent="onSubmit">
          
        <!-- Switch -->
        <div class="form-group">
          <label class="col-sm-2 control-label">开关</label>
          <div class="col-sm-4">
              
            <label class="radio-inline">
              <input type="radio" name="switch" :value="false" v-model="open"> 关闭
            </label>

            <label class="radio-inline">
              <input type="radio" name="switch" :value="true" v-model="open"> 开启
            </label>

          </div>

          <span class="col-sm-6 help-block">
            设置是否开启问答应用，关闭后整个问答接口都将拒绝访问。
          </span>

        </div>

        <!-- apply_amount -->
        <div class="form-group">
          <label class="col-sm-2 control-label">申请精选金额</label>
          <div class="col-sm-4">
            <input type="number" min="0" class="form-control" v-model="apply_amount" placeholder="输入申请精选金额">
          </div>
          <span class="col-sm-6 help-block">设置申请精选需要支付的钱，单位为真实货币「分」，前台展示为根据金币比例换算后的数值和单位。</span>
        </div>

        <!-- onlookers_amount -->
        <div class="form-group">
          <label class="col-sm-2 control-label">围观金额</label>
          <div class="col-sm-4">
            <input type="number" class="form-control" min="0" placeholder="输入围观金额" v-model="onlookers_amount">
          </div>
          <span class="col-sm-6 help-block">
            设置答案围观金额，单位为真实货币「分」，前台展示为根据金币比例换算后的数值和单位。
          </span>
        </div>
        
        <!-- 匿名规则 -->
        <div class="form-group">
            <label class="control-label col-xs-2">匿名规则</label>
            <div class="col-xs-4">
                <textarea class="form-control" v-model="anonymity_rule"></textarea>
            </div>
            <div class="col-xs-6 help-block">
                匿名规则
            </div>
        </div>

        <!-- Submit -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button v-if="isSubmit" class="btn btn-primary" disabled="disabled">
              <ui-loading></ui-loading>
              提交...
            </button>
            <button v-else class="btn btn-primary" type="submit">
              提交
            </button>
          </div>
        </div>

      </form>

      <ui-alert :type="message.type" v-show="message.open">
        {{ message.data | plusMessageFirst('操作失败!') }}
      </ui-alert>

    </div>

  </div>
</template>

<script>
import { admin } from '../../../axios';
export default {
  name: 'module-app-switch',
  data: () => ({
    isSubmit: false,
    open: false,
    apply_amount: 0,
    onlookers_amount: 0,
    anonymity_rule: '',
    message: {
      open: false,
      type: '',
      data: {}
    }
  }),

  methods: {
    publishMessage (data, type, ms = 3000) {
      this.message = { open: true, data, type };
      setTimeout(() => {
        this.message.open = false;
      }, ms);
    },

    onSubmit() {
      this.isSubmit = true;
      admin.put('/switch', {
        switch: this.open,
        apply_amount: this.apply_amount,
        onlookers_amount: this.onlookers_amount,
        anonymity_rule: this.anonymity_rule,
      }, {
        validateStatus: status => status === 201
      }).then(({ data }) => {
        this.isSubmit = false;
        this.publishMessage(data, 'info');
      }).catch(({ response: { data = {} } = {} } = {}) => {
        this.isSubmit = false;
        this.publishMessage(data, 'danger');
      });
    }
  },

  created() {
    admin.get('/switch', {
      validateStatus: status => status === 200
    }).then(({ data }) => {
      this.open = data.switch;
      this.apply_amount = data.apply_amount;
      this.onlookers_amount = data.onlookers_amount;
      this.anonymity_rule = data.anonymity_rule;
    }).catch(({ response: { data = {} } = {} } = {}) => {
      this.publishMessage(data, 'danger');
    });
  }
};
</script>
