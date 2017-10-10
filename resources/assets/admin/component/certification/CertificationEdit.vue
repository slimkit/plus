<style lang="css" module>
    .loadding {
        text-align: center;
        font-size: 42px;
        padding-top: 50px;
    }
    .loaddingIcon {
        animation-name: "TurnAround";
        animation-duration: 1.4s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }
    .attachmentBox {
        display: inline-block;
        width: 200px;
        height: 100px;
        margin-bottom: 10px;
    }
</style>
<template>
        <div class="container-fluid" style="margin-top:10px;">
          <div class="panel panel-default">
            <div class="panel-heading">
                <router-link type="button" class="btn btn-primary btn-sm" :to="{name: 'certification:users'}">返回</router-link>
            </div>
            <div class="panel-body">
                <!-- 加载动画 -->
                <div v-show="loadding" :class="$style.loadding">
                    <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
                </div>

                <div class="form-horizontal" v-show="!loadding">
                    <div class="form-group">
                        <label class="control-label col-md-2">用户ID</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="id" disabled>
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，用户ID
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2"><span class="text-danger">*</span>真实姓名</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="certification.name">
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，真实姓名
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2"><span class="text-danger">*</span>手机号码</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="certification.phone">
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，真实姓名
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2"><span class="text-danger">*</span>身份证号</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="certification.number">
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，身份证号
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2"><span class="text-danger">*</span>认证类型</label>
                        <div class="col-md-5">
                          <select class="form-control" v-model="certification.type" disabled>
                            <option :value="categroy.name" v-for="categroy in categories">{{ categroy.display_name }}</option>
                          </select>
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，认证类型
                        </span>
                    </div>
                    <div class="form-group" v-show="certification.type == 'org'">
                        <label class="control-label col-md-2"><span class="text-danger">*</span>组织名称</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="certification.org_name">  
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，组织名称
                        </span>
                    </div>
                    <div class="form-group" v-show="certification.type == 'org'">
                        <label class="control-label col-md-2"><span class="text-danger">*</span>组织地址</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="certification.org_address">  
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，组织地址
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2"><span class="text-danger">*</span>认证描述</label>
                        <div class="col-md-5">
                          <textarea class="form-control" v-model="certification.desc"></textarea>
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，认证描述
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-2"><span class="text-danger">*</span>认证附件：</label>
                        <div class="col-md-5">
                          <a href="javascript:;" class="thumbnail text-center" :class="$style.attachmentBox" @click="triggerUpload">
                            <img :src="attachmentUrl" v-if="attachmentUrl" style="height:100%;width:100%;">
                            <i class="glyphicon glyphicon-upload" style="margin-top:42px;font-size:16px;" v-else></i>
                          </a>
                        </div>
                        <input type="file" ref="clickinput" @change="uploadAttachment" accept="image/gif,image/jpeg,image/jpg,image/png" style="display:none;">
                        <span class="col-md-5 help-block">附件格式：gif, jpg, jpeg, png； 附件大小：不超过10M</span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"></label>
                        <div class="col-md-5">
                          <button class="btn btn-primary btn-sm" 
                        @click.prevent="updateCertification" data-loading-text="提交中" autocomplete="off" id="edit-btn">确认</button>   
                        </div>
                        <div class="col-md-5">
                          <span class="text-danger" v-show="message.error">{{ message.error }}</span>
                          <span class="text-success" v-show="message.success">{{ message.success }}</span>
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
const PersonalCertificationEdit = {
    data: () => ({
        loadding: true,
        message: {
            error: null,
            success: null,
        },
        categories: {},
        id: null,
        attachmentUrl: null,
        certification: {
            username: '',
            name: '',
            phone: '',
            number: '',
            files: [],
            org_name: '',
            org_address: '',
            desc: '',
            type:'user',
        },
    }),
    methods: {
        getCertificationCategories () {
          request.get(
            createRequestURI('certification/categories'),
            {validateStatus: status => status === 200}
          ).then(response => {
            this.categories = response.data;
          }).catch(({ response: { data: { errors = ['加载认证详情失败'] } = {} } = {} }) => {
          }); 
        },
        getCertification (id) {
          this.loadding = true;
          request.get(
            createRequestURI('certifications/' + id),
            { validateStatus: status => status === 200 }
          ).then(response => {

            this.loadding = false;
            var data = response.data;
            
            this.certification.username = data.user.name;
            this.certification.name = data.data.name;
            this.certification.phone = data.data.phone;
            this.certification.number = data.data.number;
            this.certification.type = data.certification_name;
            this.certification.desc = data.data.desc;
            this.certification.files = data.data.files;
            this.attachmentUrl = '/api/v2/files/' + this.certification.files[0]

            if ( data.certification_name === 'org' ) {
                this.certification.org_name = data.data.org_name;
                this.certification.org_address = data.data.org_address;
            }

          }).catch(response => {
            console.log(response);
          });
        },
        updateCertification (e) {
          $('#edit-btn').button('loading');
          request.patch(
            createRequestURI('certifications/' + this.id),
            { ...this.certification },
            {validateStatus: status => status === 201}
          ).then(({ data: { message: [ message ] = [] } }) => {
            $('#edit-btn').button('reset');
            this.message.success = message;
          }).catch(({ response: { data = {} } = {} }) => {
            $('#edit-btn').button('reset');
            let Message = new plusMessageBundle(data);
            this.message.error = Message.getMessage();
          });
        },
        /**
         * 关闭提示弹层
         */
        offAlert () {
            this.errorMessage = this.successMessage = '';
        },
        uploadAttachment (e) {
            var that = this;
            let file = e.target.files[0]; 
            let param = new FormData();
            param.append('file', file);
            //  设置请求头
            let config = {
                headers: { 
                    'Content-Type': 'multipart/form-data',
                    'Authorization': 'Bearer ' + window.TS.token 
                }
            };
            let reader = new FileReader(); 
            reader.readAsDataURL(file); 
            reader.onload = function(e) {
               request.post('/api/v2/files', param, config)
               .then((response) => {
                  const { id: id, message: [message] = [] } = response.data;
                  that.certification.files = [id];
                  that.attachmentUrl = `/api/v2/files/${id}`;
                }).catch(({ response: { data = {} } = {} }) => {
                  let Message = new plusMessageBundle(data);
                  that.message.error = Message.getMessage();
                });
            }
        },
        triggerUpload () {
          this.$refs.clickinput.click();
        }
    },
    created () {
      this.id = this.$route.params.certification;
      //获取认证栏目
      this.getCertificationCategories();
      //获取认证详情
      this.getCertification(this.id);
    },
};
export default PersonalCertificationEdit;
</script>
