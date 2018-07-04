<style lang="css" module>
.question-editor {
  resize: none;
}
.question-editor-preview {
  width: 100%;
  height: auto;
}
</style>

<template>
    <div class="container-fluid" style="margin-top:10px;">
		<div class="panel panel-default">
			<div class="panel-heading">
				用户注册设置
			</div>
			<div class="panel-body">
			  <loading :loadding="loadding"></loading>
			    <div class="form-horizontal" v-show="!loadding">
			      <div class="form-group">
					<label class="col-sm-3 control-label">注册方式</label>
			      	<div class="col-sm-5">
			      		<div class="input-group">
			      			<div class="">
							    <label class="radio-inline">
							    	<input type="radio" name="type" checked="checked" value="all" v-model="type" /> 开放注册
							    </label>
									<label class="radio-inline">
							    	<input type="radio" name="type" disabled="disabled" value="invited" v-model="type" /> 仅邀请注册
							    </label>
<!-- 							    <label class="radio-inline">
							    	<input type="radio" name="type" disabled="disabled" value="thirdPart" v-model="type" /> 仅第三方绑定
							    </label> -->
							</div>
						</div>
				      </div>
		     		<div class="col-md-4">
		     			<span class="help-block">注册方式</span>
		     		</div>	
			     	</div>
			     	<div class="form-group">
						<label class="col-sm-3 control-label">注册类型</label>
				      	<div class="col-sm-5">
				      		<div class="input-group">
				      			<div class="">
								    <label class="radio-inline">
								    	<input type="radio" name="method" value="mobile-only" v-model="method" /> 仅手机
								    </label>
										<label class="radio-inline">
								    	<input type="radio" name="method" value="mail-only" v-model="method" /> 仅邮箱
								    </label>
			<!-- 					    <label class="radio-inline">
								    	<input type="radio" name="method" value="all" v-model="method" /> 手机或邮箱或第三方
								    </label> -->
								</div>
							</div>
					    </div>
			     		<div class="col-md-4">
			     			<span class="help-block">账号注册时使用的类型</span>
			     		</div>	
			     	</div>
			     	<div class="form-group">
						<label class="col-sm-3 control-label">完善资料</label>
				      	<div class="col-sm-5">
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
			     		<div class="col-md-4">
			     			<span class="help-block">完善资料，注册时强制完善，不需要强制完善</span>
			     		</div>	
			     	</div>
			     	<div class="form-group">
						<label class="col-sm-3 control-label">服务条款和隐私政策</label>
				      	<div class="col-sm-5">
				      		<div class="input-group">
				      			<div class="">
								    <label class="radio-inline">
								    	<input type="radio" name="showTerms" :value="true" v-model="showTerms" /> 开启
								    </label>
										<label class="radio-inline">
								    	<input type="radio" name="showTerms" checked="checked" :value="false" v-model="showTerms" /> 关闭
								    </label>
								</div>
							</div>
					    </div>
			     		<div class="col-md-4">
			     			<span class="help-block">服务条款和隐私政策</span>
			     		</div>		
			     	</div>
			     	<div class="form-group" v-if="showTerms">
			     		<label  class="col-sm-3 control-label" for="rule-content">条款内容</label>
			     		<div class="col-sm-5">
				          <textarea 
				          class="form-control question-editor" 
				          rows="12" 
				          placeholder="输入内容..." 
				          v-model="content" 
				          autofocus 
				          required>
				          </textarea>
			     		</div>
			     		<div class="col-md-4">
			     			<span class="help-block">支持 Markdown 格式</span>
			     		</div>		
			     	</div>
			     	<!-- Button -->
			      <div class="form-group">
			        <div class="col-sm-offset-3 col-sm-4">
			          <button v-if="loading" type="button" class="btn btn-primary" disabled="disabled">
			            <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
			          </button>
			          <button v-else type="button" class="btn btn-primary" @click="saveConfig">保存设置</button>
			        </div>
			        <div class="col-sm-4">
			        	<p class="text-success">{{ message }}</p>
			        </div>
			      </div>
			    </div>
			</div>
		</div>
	</div>
</template>

<script>
import request, { createRequestURI } from "../../util/request";
import lodash from "lodash";
import markdownIt from "markdown-it";

const md = markdownIt({
	html: false
});
const RegisterSetting = {
	name: "question-edit",
	data: () => ({
		loadding: true,
		showTerms: false,
		fixed: "need",
		method: "all",
		type: "all",
		loading: false,
		message: "",
		content: ""
	}),
	computed: {
		preview() {
			return md.render(this.content);
		}
	},
	methods: {
		saveConfig() {
			this.loading = true;
			const { showTerms, fixed, method, type, content } = this;
			let data = {};
			if (showTerms) {
				data.content = content;
			}
			data.fixed = fixed;
			data.showTerms = showTerms;
			data.method = method;
			data.type = type;
			request
				.post(
					createRequestURI("users/register-setting"),
					{
						...data
					},
					{
						validateStatus: status => status === 201
					}
				)
				.then(({ data = {} }) => {
					this.loading = false;
					this.message = data.message;
					setTimeout(() => {
						this.message = "";
					}, 2000);
				})
				.catch(error => {
					this.loading = false;
					console.log(error);
				});
		}
	},

	created() {
		request
			.get(createRequestURI("users/register-setting"), {
				validateStatus: status => status === 200
			})
			.then(({ data = {} }) => {
				this.loadding = false;
				this.showTerms = data.showTerms;
				this.method = data.method;
				this.fixed = data.fixed;
				this.type = data.type;
				this.content = data.content ? data.content : "# 服务条款及隐私政策";
			});
	}
};

export default RegisterSetting;
</script>
