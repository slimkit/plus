<style lang="css" module>
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
        认证修改
        <router-link tag="a" class="btn btn-link pull-right btn-xs" :to="{name: 'certification:users'}" role="button">
          返回
        </router-link>
      </div>
      <div class="panel-body">
        <!-- 加载动画 -->
        <loading :loadding="loadding"></loading>

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
              <select class="form-control" v-model="certification.type">
                <option :value="categroy.name" v-for="categroy in categories">{{ categroy.display_name }}</option>
              </select>
            </div>
            <span class="col-md-5 help-block" id="phone-help-block">
                          必填，认证类型
                        </span>
          </div>
          <div class="form-group" v-show="certification.type == 'org'">
            <label class="control-label col-md-2"><span class="text-danger">*</span>结构名称</label>
            <div class="col-md-5">
              <input type="text" class="form-control" v-model="certification.org_name">
            </div>
            <span class="col-md-5 help-block" id="phone-help-block">
                          必填，组织名称
                        </span>
          </div>
          <div class="form-group" v-show="certification.type == 'org'">
            <label class="control-label col-md-2"><span class="text-danger">*</span>结构地址</label>
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
              <a href="javascript:;" class="thumbnail text-center pull-left" :class="$style.attachmentBox"
                 @click="triggerUpload(1)">
                <img :src="upload.front" v-if="upload.front" style="height:100%;width:100%;">
                <i class="glyphicon glyphicon-upload" style="margin-top:42px;font-size:16px;" v-else></i>
              </a>
              <!-- 反面 -->
              <a href="javascript:;" class="thumbnail text-center pull-right" :class="$style.attachmentBox"
                 @click="triggerUpload(2)" v-show="certification.type =='user'">
                <img :src="upload.back" v-if="upload.back" style="height:100%;width:100%;">
                <i class="glyphicon glyphicon-upload" style="margin-top:42px;font-size:16px;" v-else></i>
              </a>
            </div>
            <input type="file" ref="clickinput" @change="uploadAttachment"
                   accept="image/gif,image/jpeg,image/jpg,image/png" style="display:none;">
            <span class="col-md-5 help-block">附件格式：gif, jpg, jpeg, png； 附件大小：不超过10M</span>
          </div>
          <div class="form-group">
            <label class="col-md-2 control-label"></label>
            <div class="col-md-5">
              <button class="btn btn-primary btn-sm"
                      @click.prevent="updateCertification" data-loading-text="提交中" autocomplete="off" id="edit-btn">确认
              </button>
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
  import request, { createRequestURI } from '../../util/request'
  import { plusMessageFirst } from '../../filters'
  import { uploadFile } from '../../util/upload'

  const PersonalCertificationEdit = {
    data: () => ({
      loadding: true,
      message: {
        error: null,
        success: null
      },
      categories: {},
      id: null,
      attachmentUrl: null,
      certification: {
        name: '',
        phone: '',
        number: '',
        files: [],
        org_name: '',
        org_address: '',
        desc: '',
        type: 'user'
      },
      upload: {
        type: 1,
        front: null,
        back: null
      }
    }),
    methods: {
      getCertificationCategories () {
        request.get(
            createRequestURI('certification/categories'),
            { validateStatus: status => status === 200 }
        ).then(response => {
          this.categories = response.data
        }).catch(({ response: { data: { errors = ['加载认证详情失败'] } = {} } = {} }) => {
          this.message.error = plusMessageFirst(errors)
        })
      },
      getCertification (id) {
        this.loadding = true
        request.get(
            createRequestURI('certifications/' + id),
            { validateStatus: status => status === 200 }
        ).then(({ data }) => {

          this.loadding = false

          this.certification.name = data.data.name
          this.certification.phone = data.data.phone
          this.certification.number = data.data.number
          this.certification.type = data.certification_name
          this.certification.desc = data.data.desc
          this.certification.files = data.data.files
          this.upload.front = '/api/v2/files/' + this.certification.files[0]

          if (data.certification_name === 'org') {
            this.certification.org_name = data.data.org_name
            this.certification.org_address = data.data.org_address
          } else {
            this.upload.back = '/api/v2/files/' + this.certification.files[1]
          }

        }).catch(response => {
          console.log(response)
        })
      },
      updateCertification (e) {
        $('#edit-btn').button('loading')
        request.patch(
            createRequestURI('certifications/' + this.id),
            { ...this.certification },
            { validateStatus: status => status === 201 }
        ).then(({ data: { message: [message] = [] } }) => {
          $('#edit-btn').button('reset')
          this.message.success = message
        }).catch(({ response: { data = {} } = {} }) => {
          $('#edit-btn').button('reset')
          this.message.error = plusMessageFirst(data)
        })
      },
      /**
       * 关闭提示弹层
       */
      offAlert () {
        this.errorMessage = this.successMessage = ''
      },
      uploadAttachment (e) {
        uploadFile(e.target.files[0], (id) => {

          let upload = this.upload
          upload[upload.type == 1 ? 'front' : 'back'] = `${window.TS.api}/files/${id}`

          let cer = this.certification

          if (cer.type == 'org') {
            cer.files = [id]
          } else {
            let length = cer.files.length
            if (length <= 0) {
              cer.files = [id]
            } else {
              if (length == 1) {
                if (upload.type == 2) {
                  cer.files.push(id)
                } else {
                  cer.files.unshift(id)
                }
              } else {
                if (upload.type == 1) {
                  cer.files.splice(0, 1)
                  cer.files.unshift(id)
                } else {
                  cer.files.splice(1, 1)
                  cer.files.push(id)
                }
              }
            }
          }
        })
      },
      triggerUpload (type) {
        this.upload.type = type
        this.$refs.clickinput.click()
      }
    },
    created () {
      this.id = this.$route.params.certification
      //获取认证栏目
      this.getCertificationCategories()
      //获取认证详情
      this.getCertification(this.id)
    }
  }
  export default PersonalCertificationEdit
</script>
