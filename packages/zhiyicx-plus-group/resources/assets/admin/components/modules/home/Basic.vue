<template>
<div class="form-horizontal">
	<div class="form-group">
		<label class="control-label col-xs-2">圈子打赏</label>
		<div class="col-xs-3">
			<label class="radio-inline">
			  <input type="radio"
					 name="reward"
					 v-model="status"
					 :value="true"> 开启
			</label>
			<label class="radio-inline">
			  <input type="radio"
					 name="reward"
					 v-model="status"
					 :value="false"> 关闭
			</label>
		</div>
		<div class="help-block col-xs-7">
			<small>圈子打赏功能 开启 用户可以进行打赏操作</small>
		</div>	
	</div>
	<div class="form-group">
		<label class="control-label col-xs-2">创建认证</label>
		<div class="col-xs-3">
			<label class="radio-inline">
			  <input type="radio" name="group"
					 v-model="need_verified"
					 :value="true"> 是
			</label>
			<label class="radio-inline">
			  <input type="radio"
					 name="group"
					 v-model="need_verified"
					 :value="false"> 否
			</label>
		</div>
		<div class="help-block col-xs-7">
			<small>用户创建圈子是否需要认证 是 用户认证后才能创建圈子 否 不需要认证</small>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-xs-2">举报处理</label>
		<div class="col-xs-3">
			<label class="radio-inline">
			  <input type="radio" name="report_handle"
					 v-model="report_handle"
					 value="founder"> 仅圈子圈主
			</label>
			<label class="radio-inline">
			  <input type="radio"
					 name="report_handle"
					 v-model="report_handle"
					 value="admin"> 仅平台管理员
			</label>
		</div>
		<div class="help-block col-xs-7">
			<small>圈子举报处理设置</small>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-xs-2"></label>
		<div class="col-xs-3">
			<button class="btn btn-primary btn-sm" @click="handleSubmitSetting">提交</button>
		</div>
		<div class="help-block col-xs-7">
		</div>
	</div>
</div>
</template>
<script>
import { admin } from '../../../axios';
export default ({
    data: () => ({
		status: null,
		need_verified: null,
		report_handle: 'founder',
    }),
	methods: {
        getConfig() {
            admin.get('config', {
                validateStatus: status => status === 200,
            }).then(({ data })=>{
				this.status = data.group_reward.status;
				this.need_verified = data.group_create.need_verified;
				this.report_handle = data.report_handle;
            }).catch(({ response: { data = { message: '获取失败' } } = {} }) => {
              	this.$store.dispatch('alert-open', { type: 'danger', message: data });
          	});
		},

        handleSubmitSetting() {
            admin.patch('config', {
                status: this.status,
                need_verified: this.need_verified,
                report_handle: this.report_handle,
			}, {
                validateStatus: status => status === 201,
            }).then(({ data })=>{
				this.$store.dispatch('alert-open', { type: 'success', message: data });
            }).catch(({ response: { data = { message: '获取失败' } } = {} }) => {
              	this.$store.dispatch('alert-open', { type: 'danger', message: data });
          	});
		}
	},

	created() {
        this.getConfig();
	}
})
</script>