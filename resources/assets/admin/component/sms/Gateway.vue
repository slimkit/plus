<template>
  <div class="component-container container-fluid">
      <div class="panel panel-default">
        <div class="panel-heading">网关开启</div>
        <div class="panel-body">
            <div class="checkbox-inline" v-for="gateway in gateways">
              <label>
               <input type="checkbox" id="blankCheckbox" aria-label="..." @change="watchCheck(gateway)" :checked="checked(gateway)"> {{ gateway }}
              </label>
            </div>
            <div class="checkbox-inline">
              <button class="btn btn-primary btn-sm" @click.prevent="updateGateway">更新</button>
            </div>
            <div class="checkbox-inline">
              <span :class="`text-${submit.type}`">{{ submit.message }}</span>
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

    disabled(gateway) {
      let gateways = this.allowedGateways;
      let index = lodash.indexOf(gateways, gateway);

      return (parseInt(index) !== -1) ? false : true;
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
