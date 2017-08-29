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
        <div :class="$style.container">
            <!-- 加载动画 -->
            <div v-show="loadding" :class="$style.loadding">
                <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
            </div>

            <div class="col-md-6 col-md-offset-3" v-show="!loadding">
                <div v-show="errorMessage" class="alert alert-danger alert-dismissible affix-top" role="alert">
                    <button type="button" class="close" @click.prevent="offAlert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ errorMessage }}
                </div>
                <div v-show="successMessage" class="alert alert-success alert-dismissible affix-top" role="alert">
                    <button type="button" class="close" @click.prevent="offAlert">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ successMessage }}
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>用户ID：</label>
                    <div class="input-group">                             
                        <input type="text" class="form-control" v-model="certification.user_id" disabled>
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" @click="openfindUserModal">查找用户</button>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>真实姓名：</label>
                    <input type="text" class="form-control" v-model="certification.name">
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>手机号：</label>
                    <input type="text" class="form-control" v-model="certification.phone">
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>身份证号：</label>
                    <input type="text" class="form-control" v-model="certification.number">
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>认证类型：</label>
                    <select class="form-control" v-model="certification.type">
                        <option :value="categroy.name" v-for="categroy in categories">{{ categroy.display_name }}</option>
                    </select>
                </div>
                <div class="form-group" v-show="certification.type == 'org'">
                    <label><span class="text-danger">*</span>组织名称：</label>
                    <input type="text" class="form-control" v-model="certification.org_name">
                </div>
                <div class="form-group" v-show="certification.type == 'org'">
                    <label><span class="text-danger">*</span>组织地址：</label>
                    <input type="text" class="form-control" v-model="certification.org_address">
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>认证描述：</label>
                    <textarea class="form-control" v-model="certification.desc"></textarea>
                </div>
                <div class="form-group">
                    <label><span class="text-danger">*</span>认证附件：</label>
                    <img :src="fileBase64" class="img-responsive" :class="$style.image">
                    <input type="file" @change="uploadAttachment" accept="image/gif,image/jpeg,image/jpg,image/png">
                    <span class="help-block" style="font-size:12px;">附件格式：gif, jpg, jpeg, png； 附件大小：不超过10M</span>
                </div>
                <div class="form-group">
                    <button class="btn btn-success btn-sm btn-block" 
                    @click.prevent="createCertification">确认</button>
                </div>
            </div>
            <!-- 查找用户 modal start -->
            <div class="modal fade" id="findUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">查找用户</h4>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                        <span class="text-danger" v-show="search.message">{{ search.message }}</span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="用户名" v-model="search.keyword" @input="search.message=''">
                            <span class="input-group-btn">
                                <button class="btn btn-default" @click="searchUser" data-loading-text="搜索中" id="serach-user-btn">搜索</button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <select class="form-control" v-show="users.length" v-model="certification.user_id">
                            <option value="" disabled>请选择用户</option>
                            <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                        </select>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" @click="selectUser">确认</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- 查找用户 modal end-->
        </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
const PersonalCertificationEdit = {
    data: () => ({
        loadding: true,
        errorMessage: '',
        successMessage: '',
        categories: {},
        fileBase64: '',
        users: [],
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
        search:{
          keyword: '',
          message: '',
        }
    }),
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

          }); 
        },
        /**
         * 创建用户认证
         * @return {[type]} [description]
         */
        createCertification () {
          request.post(
            createRequestURI('certifications'),
            { ...this.certification },
            { validateStatus: status => status === 201 }
          ).then(({ data: { message: [ message ] = [] } }) => {
            this.successMessage = message;
          }).catch(({ response: { data = {} } = {} }) => {
            const { user_id = [], name = [], desc = [], files = [], phone = [], number = [], org_address = [], org_name = [], message = [] } = data;
            const [ errorMessage ] = [...user_id, ...name, ...desc, ...files, ...phone, ...number, ...org_address, ...org_name, ...message];
            this.errorMessage = errorMessage;
            this.adding = false;
          });
        },
        /**
         * 搜索用户
         */
        searchUser () {
        if ( !this.search.keyword ) {
          this.search.message = '请输入搜索关键字';
          return;
        }
        $('#serach-user-btn').button('loading');
          request.get(
            createRequestURI('find/nocertification/users?keyword=' + this.search.keyword),
            { validateStatus: status => status === 200 }
          ).then(response => {
            $('#serach-user-btn').button('reset');
            this.users = response.data;
          }).catch(({ response: { data: { message: [ message ] = [] } = {} } = {} }) => {
            $('#serach-user-btn').button('reset');
            this.search.message = message;
          });
        },
        /**
         * 上传附件
         */
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
           that.fileBase64 = e.target.result;
           request.post('/api/v2/files', param, config)
            .then((response) => {
                const { id: id, message: [message] = [] } = response.data;
                that.certification.files = [id];
            }).catch((error) => {
                console.log(error);
            });
          }
        },
        /**
         * 打开搜索用户modal
         */
        openfindUserModal () {
          $('#findUserModal').modal('show');
        },
        /**
         * 选择用户
         */
        selectUser () {
          if ( !this.users.length ) {
            this.search.message = '请输入搜索关键字';
            return;  
          }
          if ( !this.certification.user_id ) {
            this.search.message = '请选择用户';
            return;
          }
          $('#findUserModal').modal('hide');
        },
        /**
         * 关闭提示弹层
         */
        offAlert () {
          this.errorMessage = this.successMessage = '';
        },
    },
    created () {
      this.getCertificationCategories();
    },

};
export default PersonalCertificationEdit;
</script>
