<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      <router-link to="/topics">
        <span class="glyphicon glyphicon-menu-left"></span>
        返回列表
      </router-link>
    </div>

    <div class="panel-body">
      <ui-loading v-if="loading"></ui-loading>
      <div v-else class="form-horizontal">

        <!-- name -->
        <div class="form-group">
          <label class="col-sm-2 control-label">话题</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" placeholder="输入话题" v-model="name">
          </div>
          <span class="col-sm-4 help-block">
            请输入话题名称。
          </span>
        </div>

        <!-- 描述 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">描述</label>
          <div class="col-sm-6">
            <textarea class="form-control" placeholder="输入话题描述" v-model="description"></textarea>
          </div>
          <span class="col-sm-4 help-block">
            请输入话题描述。
          </span>
        </div>

        <!-- 头像 -->
        <div class="form-group">
          <label class="col-sm-2 control-label">头像</label>
          <div class="col-sm-6">
            <vue-core-image-upload
              class="btn btn-default"
              crop="local"
              inputOfFile="avatar"
              @imagechanged="imagechanged"
              @errorhandle="errorhandle"
              :max-file-size="5242880"
              :cropBtn="{ok: '上传', cancel: '取消'}"
              :isXhr="false"
              :maxWidth="150"
              :maxHeight="150"
              inputAccept="image/*"
              text="点击上传">
            </vue-core-image-upload>
          </div>
          <span class="col-sm-4 help-block">
            需要更新头像，请选择头像后点击上传按钮更新。
          </span>
        </div>
        <div class="form-group" v-show="avatar">
          <div class="col-sm-offset-2 col-sm-10">
            <img :src="avatar" class="thumbnail" width="100px" height="100px">
          </div>
        </div>  
        <!-- 提交按钮 -->
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">

            <ui-process-button type="button" class="btn btn-primary" @click="handleSubmit">
              <template slot-scope="{ processing }">
                <template v-if="processing">
                  <ui-loading></ui-loading>
                  提交中...
                </template>
                <template v-else>提交</template>
              </template>
            </ui-process-button>

          </div>
        </div>

      </div>

      <ui-alert :type="message.type" v-show="message.open">
        {{ message.data | plusMessageFirst('操作失败！') }}
      </ui-alert>

    </div>

  </div>
</template>

<script>
import { admin } from '../../axios';
import VueCoreImageUpload from 'vue-core-image-upload'
export default {
  components: {
    'vue-core-image-upload': VueCoreImageUpload,
  },
  name: 'topic-edit',
  data: () => ({
    loading: true,
    name: '',
    description: '',
    avatar: null,
    message: {
      open: false,
      type: '',
      data: {},
    },
    interval: null,

  }),
  computed: {
    id () {
      const { id } = this.$route.params;

      return parseInt(id);
    }
  },
  methods: {
    publishMessage (data, type, ms = 3000) {
      clearInterval(this.interval);

      this.message = { open: true, type, data };
      this.interval = setInterval(() => {
        this.message.open = false;
      }, ms);
    },

    handleSelectAvatar (event) {
      const { files: [ file = null ] = [] } = event.target;
      this.file = file;
    },

    handleUploadAvarar ({ stopProcessing = () => {} }) {
      const file = this.file;
      
      if (! file) {
        stopProcessing();
        this.publishMessage({ message: '请选择头像文件' }, 'warning');

        return ;
      }

      const params = new FormData();
      params.append('avatar', file, file.name);
      admin.post(`/topics/${this.id}/avatar`, params, {
        headers: { 'Content-Type': 'multipart/form-data' },
        validateStatus: status => status === 204,
      }).then(() => {
        stopProcessing();
        this.avatar = URL.createObjectURL(file);
        this.publishMessage({ message: '头像上传成功' }, 'success');
      }).catch(({ response: { data } = {} }) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });
    },

    handleSubmit ({ stopProcessing = () => {} }) {
      admin.patch(`/topics/${this.id}`, { name: this.name, description: this.description }, {
        validateStatus: status => status === 201,
      }).then(() => {
        stopProcessing();
        this.publishMessage({ message: '更新成功' }, 'success');
      }).catch(({ response: { data } = {} }) => {
        stopProcessing();
        this.publishMessage(data, 'danger');
      });
    },

    imagechanged(res) {
      let blob = this.dataURItoBlob(res);
      console.log(blob);
      const params = new FormData();
      params.append('avatar', blob);
      admin.post(`/topics/${this.id}/avatar`, params, {
        headers: { 'Content-Type': 'multipart/form-data' },
        validateStatus: status => status === 204,
      }).then(() => {
        this.publishMessage({ message: '头像上传成功' }, 'success');
        this.avatar = res;
      }).catch(({ response: { data } = {} }) => {
        this.publishMessage(data, 'danger');
      });
    },
    // file base64 to blob
    dataURItoBlob (dataURI) {
      var byteString = atob(dataURI.split(',')[1]);
      var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
      var ab = new window.ArrayBuffer(byteString.length);
      var ia = new window.Uint8Array(ab);
      for (var i = 0; i < byteString.length; i++) {
          ia[i] = byteString.charCodeAt(i);
      }
      return new window.Blob([ab], {type: mimeString});
    },

    errorhandle (error) {
      this.publishMessage({message: error}, 'danger');
    }
  },
  created () {
    this.loading = true;
    admin.get(`/topics/${this.id}`, {
      validateStatus: status => status === 200,
    }).then(({ data }) => {
      const { name, description, avatar } = data;
      this.avatar = avatar;
      this.name = name;
      this.description = description;
      this.loading = false;
    }).catch(({ response: { data } = {} }) => {
      this.publishMessage(data, 'danger');
    });
  }
};
</script>
