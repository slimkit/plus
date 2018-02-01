<template>
	<div class="container-fluid" style="margin-top:10px;">
		<div class="panel panel-default">
			<div class="panel-heading">
				基础设置 - 开关设置
			</div>
			<loading :loadding="loading"></loading>
			<div class="panel-body" v-show="!loading">
				<div class="form-horizontal">
					<div class="form-group">
						<label for="" class="col-md-1 control-label">充值</label>
						<div class="col-md-2">
							<label class="radio-inline">
							  <input type="radio" name="recharge" :value="radio.on" v-model="walet.recharge"> 开启
							</label>
							<label class="radio-inline">
							  <input type="radio" name="recharge" :value="radio.off" v-model="walet.recharge"> 关闭
							</label>
						</div>
						<div class="col-md-9 help-block">充值开启与关闭</div>
					</div>
					<div class="form-group">
						<label for="" class="col-md-1 control-label">提现</label>
						<div class="col-md-2">
							<label class="radio-inline">
							  <input type="radio" name="cash" :value="radio.on" v-model="walet.cash"> 开启
							</label>
							<label class="radio-inline">
							  <input type="radio" name="cash" :value="radio.off" v-model="walet.cash"> 关闭
							</label>
						</div>
						<div class="col-md-9 help-block">提现开启与关闭</div>
					</div>
                    <div class="form-group">
                        <label for="" class="col-md-1 control-label">转换积分</label>
                        <div class="col-md-2">
                            <label class="radio-inline">
                              <input type="radio" name="transform" :value="radio.on" v-model="walet.transform"> 开启
                            </label>
                            <label class="radio-inline">
                              <input type="radio" name="transform" :value="radio.off" v-model="walet.transform"> 关闭
                            </label>
                        </div>
                        <div class="col-md-9 help-block">钱包余额转换积分的开关</div>
                    </div>
					<div class="form-group">
						<label for="" class="col-md-1 control-label"></label>
						<div class="col-md-11">
							<button class="btn btn-primary" @click="handleSubmit">确认</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import request, { createRequestURI } from '../../util/request';

export default {
	data:()=>({
	  loading: true,
      radio: {
        on: true,
        off: false,
      },
      walet: {
        recharge: true,
        cash: true,
        transform: true,
      }
	}),
	methods: {
      getWalletSwitch () {
        request.get(createRequestURI('wallet/switch'), {
          validateStatus: status => status === 200,
        }).then(({ data = {} }) => {
          this.loading = false;
          this.walet.recharge = data.recharge.status;
          this.walet.transform = data.transform.status;
          this.walet.cash = data.cash.status;
        }).catch(({ response: { data = { message: '加载钱包开关失败' } } = {} }) => {
          this.loading = false;
          window.alert(message);
        });
      },
	  handleSubmit () {
        request.patch(
        	createRequestURI('wallet/switch'), 
        	{ switch: this.walet },
        	{ validateStatus: status => status === 201 }
        ).then(({ data: { message  } }) => {
        	window.alert(message);
        }).catch(({ response: { data = { message: '更新钱包开关失败' } } = {} }) => {
          this.loading = false;
          window.alert(message);
        });
	  }
	},
	created () {
	  this.getWalletSwitch();
	}
}
</script>
