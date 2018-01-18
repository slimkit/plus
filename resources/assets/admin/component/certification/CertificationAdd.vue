<style lang="css" module>
    .avatar {
      height:20px;
      width:20px;
      display:inline-block;
      /*margin-right:20px;*/
    }
    .attachmentBox {
        display: inline-block;
        width: 48%;
        height: 100px;
        margin-bottom: 10px;
    }
</style>

<template>
        <div class="container-fluid" style="margin-top:10px;">
          <div class="panel panel-default">
              <div class="panel-heading">
                认证添加
                <router-link tag="a" class="btn btn-link pull-right btn-xs" :to="{name: 'certification:users'}" role="button">
                  返回
                </router-link>
              </div>
              <div class="panel-body">
                <div v-show="!loadding" class="form-horizontal">
                    <div class="form-group">
                     <label class="col-md-2 control-label"><span class="text-danger">*</span>用户ID：</label>
                     <div class="col-md-5">
                       <div class="input-group-btn">
                          <div class="row">
                              <div class="col-md-6">
                                <input type="text" class="form-control" placeholder="用户ID" v-model="certification.user_id" disabled="disabled">
                              </div>
                              <!-- user search satart -->
                              <div class="col-md-6">
                                <search-user :get-user-id="getUserId"></search-user>
                              </div>
                              <!-- user search end -->
                          </div>
                        </div>
                     </div>
                     <span class="col-md-5 help-block" id="phone-help-block">
                      必填，用户ID。
                     </span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"><span class="text-danger">*</span>真实姓名</label>
                        <div class="col-md-5">
                           <input type="text" class="form-control" v-model="certification.name">
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，真实姓名
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"><span class="text-danger">*</span>手机号码</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="certification.phone">
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，手机号码
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"><span class="text-danger">*</span>身份证号</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="certification.number">
                        </div> 
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，身份证号
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"><span class="text-danger">*</span>认证类型</label>
                        <div class="col-md-5">
                          <select class="form-control" v-model="certification.type">
                              <option :value="categroy.name" v-for="categroy in categories">{{ categroy.display_name }}</option>
                          </select>
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，认证类型
                        </span>
                    </div>
                    <div class="form-group" v-show="certification.type == 'org'">
                        <label class="col-md-2 control-label"><span class="text-danger">*</span>结构名称</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="certification.org_name">
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，组织名称
                        </span> 
                    </div>
                    <div class="form-group" v-show="certification.type == 'org'">
                        <label class="col-md-2 control-label"><span class="text-danger">*</span>结构地址</label>
                        <div class="col-md-5">
                          <input type="text" class="form-control" v-model="certification.org_address">
                        </div>
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，组织地址
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"><span class="text-danger">*</span>认证描述</label>
                        <div class="col-md-5">
                          <textarea class="form-control" v-model="certification.desc"></textarea>
                        </div>  
                        <span class="col-md-5 help-block" id="phone-help-block">
                          必填，认证描述
                        </span>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label"><span class="text-danger">*</span>认证附件</label>
                        <div class="col-md-5">
                          <!-- 正面 -->
                          <a href="javascript:;" class="thumbnail text-center pull-left" :class="$style.attachmentBox" @click="triggerUpload(1)">
                            <img :src="upload.front" v-if="upload.front" style="height:100%;width:100%;">
                            <i class="glyphicon glyphicon-upload" style="margin-top:42px;font-size:16px;" v-else></i>
                          </a>
                          <!-- 反面 -->
                          <a href="javascript:;" class="thumbnail text-center pull-right" :class="$style.attachmentBox" @click="triggerUpload(2)"  v-show="certification.type =='user'">
                            <img :src="upload.back" v-if="upload.back" style="height:100%;width:100%;">
                            <i class="glyphicon glyphicon-upload" style="margin-top:42px;font-size:16px;" v-else></i>
                          </a>
                        </div>
                        <input type="file" ref="clickinput" @change="uploadAttachment" accept="image/gif,image/jpeg,image/jpg,image/png" style="display:none;">
                        <span class="col-md-5 help-block">必须上传，附件格式：gif, jpg, jpeg, png； <br/> 附件大小：不超过10M</span>
                    </div>
                    <div class="form-group">
                       <label class="col-md-2 control-label"></label>
                      <div class="col-md-5">
                        <button class="btn btn-primary btn-sm" 
                        @click.prevent="createCertification" data-loading-text="提交中" autocomplete="off" id="add-btn">确认</button>
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
import { plusMessageFirst } from '../../filters';
import { uploadFile } from '../../util/upload';
const PersonalCertificationEdit = {
    data: () => ({
        loadding: true,
        users: [],
        categories: [],
        attachmentUrl: '',
        certification: {
          user_id: '',
          name: '',
          phone: '',
          number: '',
          files: [],
          org_name: '',
          org_address: '',
          desc: '',
          type:'user',
        },
        upload: {
          type:1,
          front: null,
          back: null,
        },
        message: {
          error: null,
          success: null,
        },
    }),
    watch: {
      'certification.type'() {
        if (this.certification.type == 'user' && this.certification.files.length == 2) {
          this.certification.files.splice(1, 1);
          this.upload.back = null;
        }
      }
    },
    methods: {
        /**
         * 获取认证类型
         * @return {[type]} [description]
         */
        getCertificationCategories () {
          request.get(
            createRequestURI('certification/categories'),
            {validateStatus: status => status === 200}
          ).then(response => {
            this.categories = response.data;
            this.loadding = false;
          }).catch(({ response: { data: { errors = ['加载认证详情失败'] } = {} } = {} }) => {
            this.loadding = false;
            this.message.error = plusMessageFirst(errors);
          }); 
        },
        /**
         * 创建用户认证
         * @return {[type]} [description]
         */
        createCertification () {
          $('#add-btn').button('loading');
          request.post(
            createRequestURI('certifications'),
            { ...this.certification },
            { validateStatus: status => status === 201 }
          ).then(data => {
            $('#add-btn').button('reset');
            this.$router.replace({ path: `/certifications/${data.data.certification_id}` });
          }).catch(({ response: { data = {} } = {} }) => {
            $('#add-btn').button('reset');
            this.message.error = plusMessageFirst(data);
          });
        },
        /**
         * 上传附件
         */
        uploadAttachment (e) {
          uploadFile(e.target.files[0], (id) => {
            
              let upload = this.upload;
              upload[upload.type == 1 ? 'front' : 'back'] = `${window.TS.api}/files/${id}`;

              let cer = this.certification;

              if (cer.type == 'org') {
                cer.files = [id];
              } else {
                let length = cer.files.length;
                if (length <= 0) {
                  cer.files = [id]
                } else {
                  if (length == 1) {
                    if (upload.type == 2) {
                      cer.files.push(id);
                    } else {
                      cer.files.unshift(id);
                    }
                  } else {
                    if (upload.type == 1) {
                      cer.files.splice(0, 1);
                      cer.files.unshift(id);
                    } else {
                      cer.files.splice(1, 1);
                      cer.files.push(id);
                    }
                  }
                }
              }
          });
        },
        getUserId (userId) {
          this.certification.user_id = userId ? userId : null;
        },
        triggerUpload (type) {
          this.upload.type = type;
          this.$refs.clickinput.click();
        },
    },
    created () {
      this.getCertificationCategories();
    },

};
export default PersonalCertificationEdit;
</script>
