<style lang="css" module>
    .container {
        padding-top: 15px;
    }
    .loadding {
        text-align: center;
        font-size: 42px;
        padding-top: 100px;
    }
    .loaddingIcon {
        animation-name: "TurnAround";
        animation-duration: 1.4s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }
    .image {
        max-width:200px;
        margin-bottom: 10px;
    }
</style>

<template>
<div class="container-fluid" style="margin-top:10px;">
  <div class="panel panel-default">
    <div class="panel-heading">
      敏感词类型添加
      <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/setting/filter-word-types" role="button">
        返回
      </router-link>
    </div>
    <div class="panel-body">
      <div class="col-md-6 col-md-offset-2">
          <div class="panel-body form-horizontal">
		          <div class="form-group">
		              <label class="col-md-2 control-label">类别名</label>
		              <div class="col-md-6">
						<input type="text" class="form-control" v-model="formData.name">
		              </div>
		              <div class="col-md-4">
		              	<div class="help-block">请输入，类别名</div>
		              </div>
		          </div>
		          <div class="form-group">
		              <label class="col-md-2 control-label">状态</label>
					  <div class="col-md-6">
						<label class="checkbox-inline">
						  <input type="radio" id="radio" value="1"v-model="formData.status"> 开启
						</label>
						<label class="checkbox-inline">
						  <input type="radio" id="radio" value="0" v-model="formData.status"> 关闭
						</label>
					  </div>
		              <div class="col-md-4">
		              	<div class="help-block">请选择，类别状态</div>
		              </div>
		          </div>
		          <div class="form-group">
					<div class="col-md-2"></div>
					<div class="col-md-6">
		                <button type="submit" 
		                @click="add" id="ok-btn" 
		                data-loading-text="提交中..." 
		                class="btn btn-primary" 
		                autocomplete="off">确认</button>
					</div>
					<div class="col-md-4">
                       <span class="text-success"  v-show="message.success">{{ message.success }}</span>
                       <span class="text-danger" v-show="message.error">{{ message.error }}</span>
					</div>	
		          </div>
	           </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import plusMessageBundle from 'plus-message-bundle';
const FilterWordCategoryAdd = {
    data: () => ({
       formData: {
        name: '',
        status: 1,
      },
      message: {
      	success: '',
      	error: '',
      }
    }),
    methods: {
      add () {
      	if (!this.formData.name) {
      		this.message.error = '请填写类别名';
      		return;
      	}
        $('#add-btn').button('loading');
        request.post(
          createRequestURI('filter-word-types'),
          { ...this.formData },
          { validateStatus: status => status === 201 }
        ).then(({ data: { message } }) => {
          $('#add-btn').button('reset');
		  this.$router.replace({ path: `/setting/filter-word-types` });
        }).catch(({ response: { data = {} } = {} }) => {
          let Message = new plusMessageBundle(data);
          this.message.error = Message.getMessage();
        });
      },
      offAlert () {
        this.errorMessage = this.successMessage = '';
      },
    },
    created () {
    },

};
export default FilterWordCategoryAdd;
</script>