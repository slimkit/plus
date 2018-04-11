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
            <button class="btn btn-default" @click='chooseImg'>上传头像</button>
            <input type="file" ref='uploadFile' style="display: none !important" @change='getImg' accept="image/gif, image/jpeg, image/png">
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
const getFileUrl = file => {
  let url = null;
  if (window.createObjectURL !== undefined) {
    // basic
    url = window.createObjectURL(file);
  } else if (window.URL !== undefined) {
    // mozilla(firefox)
    url = window.URL.createObjectURL(file);
  } else if (window.webkitURL !== undefined) {
    // webkit or chrome
    url = window.webkitURL.createObjectURL(file);
  }
  return url;
};
import { admin } from '../../axios';
export default {
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
    getImg(e) {
      const vm = this;
      const files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      const img = files[0];
      this.$ImgCropper.show({
        url: getFileUrl(img),
        round: false,
        onCancel() {
          vm.$refs.uploadFile.value = null;
        },
        onOk(canvas) {
          canvas.toBlob(blob => {
          let param = new FormData();
            param.append('avatar', blob);
            let config = { headers: { "Content-Type": "multipart/form-data" } };
            admin
              .post(`/topics/${vm.id}/avatar`, param, config)
              .then(({data: { id }}) => {
                vm.$refs.uploadFile.value = null;
                var reader = new FileReader();
                reader.readAsDataURL(blob); 
                reader.onloadend = function() {              
                  vm.avatar = reader.result;
                }
              });
          }, img.type || "image/png");
        }
      });
    },
    errorhandle (error) {
      this.publishMessage({message: error}, 'danger');
    },

    chooseImg() {
      this.$refs.uploadFile.click();
    },
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
