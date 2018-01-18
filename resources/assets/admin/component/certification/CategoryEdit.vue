<template>
        <div class="container-fluid" style="margin-top:10px;">
            <div class="panel panel-default">
              <div class="panel-heading">
                认证类型修改
                <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/certifications/categories" role="button">
                  返回
                </router-link>
              </div>
              <div class="panel-body">
                <!-- 加载动画 -->
                <loading :loadding="loadding"></loading>
                <div class="col-md-6 col-md-offset-3" v-show="!loadding">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="">类型名：</label>
                            <input type="text" class="form-control" v-model="category.name" disabled>
                        </div>
                        <div class="form-group">
                            <label for="">显示名：</label>
                            <input type="text" class="form-control" v-model="category.display_name">
                        </div>
                        <div class="form-group">
                            <label for="">图标</label>
                            <div class="input-group">
                              <input type="text" class="form-control" :value="category.icon" disabled="true">
                              <div class="input-group-btn">
                                <button class="btn btn-primary" @click.prevent="preview" :disabled="category.icon ? false : true">预览</button>
                                <button class="btn btn-default" @click.prevent="triggerFileInput" id="upload-btn" data-loading-text="上传中">上传</button>
                                <input type="file" id="icon-file-input" style="display:none;" @change="uploadIcon">
                              </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">描述：</label>
                            <textarea class="form-control" v-model="category.description"></textarea>
                        </div>
                        <div class="form-group">
                          <input type="submit"
                               value="提交"
                               class="btn btn-primary btn-sm"
                               id="submit-btn" 
                               @click.prevent="updateCertificationCategory(category.name)"
                               data-loading-text="提交中" autocomplete="off">
                           <span class="text-success pull-right"  v-show="message.success">{{ message.success }}</span>
                           <span class="text-danger pull-right" v-show="message.error">{{ message.error }}</span>
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
const CategoryEditComponent = {
    data: () => ({
      loadding: true,
      message: {
        success: null,
        error: null,
      },
      category:{
        name: '',
        display_name: '',
        description: '',
        icon: '',
      },
    }),
    methods: {
        getCertificationCategory (name) {
          this.loadding = true;
          request.get(
            createRequestURI('certification/categories/'+name),
            {validateStatus: status => status === 200}
          ).then(response => {
            this.loadding = false;
            this.category = response.data;
          }).catch(({ response: { data: { errors = ['获取认证详情失败'] } = {} } = {} }) => {
            this.loadding = false;
            this.message.error = plusMessageFirst(errors);
          });
        },
        updateCertificationCategory (name) {
          $('#submit-btn').button('loading');
          request.put(
            createRequestURI('certification/categories/' + name),
            { ...this.category },
            { validateStatus: status => status === 201 }
          ).then(({ data: { message: [ message ] = [] } }) => {
            $('#submit-btn').button('reset');
            this.message.success = message;
          }).catch(({ response: { data = {} } = {} }) => {
            this.message.error = plusMessageFirst(data);
          });
        },
        triggerFileInput () {
          $('#icon-file-input').click();
        },
        preview () {
          window.open(this.category.icon);
        },
        uploadIcon () {
            let name = this.$route.params.name;
            let e = window.event || arguments[0];
            let that = this;
            let file = e.target.files[0]; 
            let param = new FormData();
            param.append('icon', file);
            // 设置请求头
            let config = {
              headers: { 
                'Content-Type': 'multipart/form-data',
                'Authorization': 'Bearer ' + window.TS.token,
              }
            };
            let reader = new FileReader(); 
            reader.readAsDataURL(file); 
            reader.onload = function(e) {
              $('#upload-btn').button('loading');
              request.post(
                createRequestURI(`certification/categories/${name}/icon/upload`),
                param,
                config,
                { validateStatus: status => status === 201 }
              ).then(response => {
                $('#upload-btn').button('reset');
                let data = response.data;
                that.category.icon = data.icon;
              }).catch(({ response: { data = { message: message = [] } } = {} }) => {
                $('#upload-btn').button('reset');
                this.message.error = plusMessageFirst(message);
              });
            }
        },
    },
    created () {
      this.getCertificationCategory(this.$route.params.name);
    },

};
export default CategoryEditComponent;
</script>
