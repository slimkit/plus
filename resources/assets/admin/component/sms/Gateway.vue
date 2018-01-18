<template>
  <div class="container-fluid" style="margin-top:10px;">
      <div class="panel panel-default">
        <div class="panel-heading">网关配置</div>
        <div class="panel-body form-horizontal">
              <!-- checkbox -->
              <div class="form-group">
                  <label class="col-sm-2 control-label">开启网关</label>
                  <div class="col-sm-3">
                    <div class="checkbox-inline"   v-for="gateway in gateways">
                      <label>
                       <input type="checkbox" id="blankCheckbox" @change="watchCheck(gateway)" :checked="checked(gateway)"> {{ gateway }}
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <span class="help-block" id="password-help">关闭开启网关</span>
                  </div>
              </div>
              <!-- button -->
              <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-1">
                      <button class="btn btn-primary btn-sm" @click.prevent="updateGateway">确定</button>
                  </div>
                  <div class="col-sm-2">
                      <span :class="`text-${submit.type}`">{{ submit.message }}</span>
                  </div>
              </div>
        </div>  
      </div>
  </div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';
import lodash from 'lodash';

const SmsGatwayComponent = {
  
  data: () => ({
    gateways:[],
    allowedGateways:[],
    checkedGateways:[],
    submit: {
      type:'',
      message: '',
    }
  }),

  methods: {

    getSmsGateways () {
      request.get(
        createRequestURI('sms/gateways'),
        { validateStatus: status => status === 200 }
      ).then(response => {
        let data = response.data;

        this.gateways = data.gateways;
        this.allowedGateways = data.allowed_gateways;
        this.checkedGateways = data.default_gateways;
      }).catch(({ response: { data: { errors = ['加载认证类型失败'] } = {} } = {} }) => {
        this.loadding = false;
      });
    },

    watchCheck (gateway) {
      let gateways = this.checkedGateways;
      let index = lodash.indexOf(gateways, gateway);

      parseInt(index) === -1 ? gateways.push(gateway) : lodash.pullAt(gateways, index);
    },

    updateGateway () {
      request.patch(
        createRequestURI('sms/update/gateways'),
        { gateways: this.checkedGateways, type: 'sms' },
        { validateStatus: status => status === 201 }
      ).then(({ data: { message: [ message ] = [] } }) => {
        this.submit.message = message;
        this.submit.type = 'success';
      }).catch(({ response: { data: { message: [ message = '提交失败' ] = [] } = {} } = {} }) => {
        this.submit.message = message;
        this.submit.type = 'danger';
      });
    },

    checked(gateway) {
      let gateways = this.checkedGateways;
      let index = lodash.indexOf(gateways, gateway);

      return (parseInt(index) !== -1) ? true : false;
    }

  },

  created() {
    this.getSmsGateways();
  }
};

export default SmsGatwayComponent;
</script>
