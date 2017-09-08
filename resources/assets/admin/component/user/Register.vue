<style lang="css" module>
.container {
  padding-top: 15px;
}
.loadding {
  text-align: center;
  font-size: 42px;
}
.loaddingIcon {
  animation-name: "TurnAround";
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}
.editor {
	width: 100%;
}
</style>

<template>
  <div class="container-fluid" :class="$style.container">
		<div class="well well-sm">
      用户注册设置
    </div>
    <div class="form-horizontal">
      <div class="form-group">
				<label class="col-sm-3 control-label">注册方式</label>
      	<div class="col-sm-7">
      		<div class="input-group">
      			<div class="">
					    <label class="radio-inline">
					    	<input type="radio" name="type" checked="checked" value="all" v-model="type" /> 开放注册
					    </label>
							<label class="radio-inline">
					    	<input type="radio" name="type" disabled="disabled" value="invited" v-model="type" /> 仅邀请注册
					    </label>
					    <label class="radio-inline">
					    	<input type="radio" name="type" disabled="disabled" value="thirdPart" v-model="type" /> 仅第三方绑定
					    </label>
					  </div>
					</div>
	      </div>
     	</div>
     	<div class="form-group">
				<label class="col-sm-3 control-label">注册类型</label>
      	<div class="col-sm-7">
      		<div class="input-group">
      			<div class="">
					    <label class="radio-inline">
					    	<input type="radio" name="method" disabled="disabled" value="mobile-only" v-model="method" /> 仅手机
					    </label>
							<label class="radio-inline">
					    	<input type="radio" name="method" disabled="disabled" value="mail-only" v-model="method" /> 仅邮箱
					    </label>
					    <label class="radio-inline">
					    	<input type="radio" name="method" checked="checked" value="all" v-model="method" /> 手机或邮箱
					    </label>
					  </div>
					</div>
	      </div>
     	</div>
     	<div class="form-group">
				<label class="col-sm-3 control-label">完善资料</label>
      	<div class="col-sm-7">
      		<div class="input-group">
      			<div class="">
					    <label class="radio-inline">
					    	<input type="radio" name="fixed" v-model="fixed" value="need" /> 注册时强制完善
					    </label>
							<label class="radio-inline">
					    	<input type="radio" name="fixed" checked="checked" value="no-need" v-model="fixed"/> 不需要强制完善
					    </label>
					  </div>
					</div>
	      </div>
     	</div>
     	<div class="form-group">
				<label class="col-sm-3 control-label">服务条款和隐私政策</label>
      	<div class="col-sm-7">
      		<div class="input-group">
      			<div class="">
					    <label class="radio-inline">
					    	<input type="radio" name="rules" value="open" v-model="rules" /> 开启
					    </label>
							<label class="radio-inline">
					    	<input type="radio" name="rules" checked="checked" value="close" v-model="rules" /> 关闭
					    </label>
					  </div>
					</div>
	      </div>
     	</div>
     	<div class="form-group" v-if="rules === 'open'">
     		<label  class="col-sm-3 control-label" for="rule-content">条款内容</label>
     		<div class="col-sm-9">
     			<div class="input-group" style="height: 400px;">
     				<VueEditor v-model="content" :value="content" @input="input" ref="editor" class="editor" />
     			</div>
     		</div>
     	</div>
    </div>
	</div>
</template>

<script>

	import VueEditor from '../edit.md/components/index';
	
	const RegisterSetting = {
		components: {
			VueEditor
		},

		data: () => ({
			rules: 'close',
			fixed: 'need',
			method: 'all',
			type: 'all',
			content: '# 服务条款及隐私政策'
		}),
		methods: {
			input(val) {
				this.content = val;
			}
		}
	};

	export default RegisterSetting;
</script>