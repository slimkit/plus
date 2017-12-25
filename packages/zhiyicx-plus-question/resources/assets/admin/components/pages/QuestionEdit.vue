<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      <button type="button" class="btn btn-default btn-sm" @click="$router.back()">返回</button>
      编辑问题
      <ui-loading v-show="getting"></ui-loading>
    </div>

    <div class="panel-body">

      <div class="form-group">
        <input type="text" class="form-control input-lg" placeholder="输入标题..." v-model="subject">
      </div>

      <div class="form-group">
        <textarea
          class="form-control question-editor"
          rows="12"
          placeholder="输入内容..."
          v-model="body"
          autofocus
          required
        >
        </textarea>
      </div>

      <div class="form-group">

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

      <ui-alert v-show="message.open" :type="message.type">
        {{ message.data | plusMessageFirst('操作失败') }}
      </ui-alert>

      <!-- 图床 -->
      <table class="table table-bordered">
        <tbody>

          <tr>
            <td>
              <input class="form-control" type="file" accept="image/jpg,image/jpeg,image/png,image/gif" @change="handleSelectImage">
            </td>
            <td>
              <ui-process-button tyle="button" class="btn btn-info" @click="handleUploadImage" :disabled="! file">
                <template slot-scope="{ processing }">
                  <template v-if="processing">
                    <ui-loading></ui-loading>
                    上传中...
                  </template>
                  <template v-else>上传图片</template>
                </template>
              </ui-process-button>

            </td>
          </tr>

          <tr v-for="image, key in images" :key="key">
            <td><img :src="image.url" style="max-width: 100%; max-height: 100px;"></td>
            <td><input type="text" class="form-control" :value="`@![${image.name}](${image.id})`" readonly=""></td>
          </tr>

        </tbody>
      </table>

      <!-- Preview -->
      <h4>预览：</h4>
      <div class="question-editor-preview" v-html="preview"></div>

    </div>

  </div>
</template>

<script>
import markdownIt from 'markdown-it';
import plusImageSyntax from 'markdown-it-plus-image';
import { api, admin } from '../../axios';

const md = markdownIt({
  html: false
}).use(plusImageSyntax, `${api.defaults.baseURL}/files/`);

export default {
  name: 'question-edit',
  data: () => ({
    getting: false,
    subject: '',
    body: '',
    images: [],
    file: null,
    message: {
      open: false,
      data: {},
      type: ''
    }
  }),
  computed: {
    id() {
      const { params: { id } } = this.$route;

      return parseInt(id);
    },
    preview() {
      return md.render(this.body);
    }
  },
  methods: {
    handleSubmit({ stopProcessing }) {
      const { subject, body } = this;
      admin.patch(`/questions/${this.id}`, { subject, body }, {
        validateStatus: status => status === 204
      }).then(() => {
        stopProcessing();
        this.message = { open: true, type: 'success', data: { message: "提交成功!" } };
      }).catch(({ response: { data } = {} } = {}) => {
        stopProcessing();
        this.message = { open: true, type: 'danger', data };
      });
    },

    handleSelectImage(event) {
      const { files: [ file = null ] = [] } = event.target;
      this.file = file;
    },

    handleUploadImage({ stopProcessing = () => {} }) {

      if (this.file === null) {
        stopProcessing();
        return;
      }

      const file = this.file;
      const params = new FormData();
      params.append('file', file, file.name);

      api.post('/files', params, {
        headers: { 'Content-Type': 'multipart/form-data' },
        validateStatus: status => status === 201
      }).then(({ data }) => {
        stopProcessing();
        this.images = [ ...this.images, {
          id: data.id,
          url: URL.createObjectURL(file),
          name: file.name
        } ];
        this.file = null;
      }).catch(({ response: { data } = {} } = {}) => {
        stopProcessing();
        this.file = null;
        this.message = { open: true, type: 'danger', data };
      });
    }
  },
  created() {
    this.getting = true;
    admin.get(`/questions/${this.id}`, {
      validateStatus: status => status === 200,
    }).then(({ data: { subject, body } = {} }) => {
      this.getting = false;
      this.subject = subject;
      this.body = body;
    }).catch(() => {
      this.getting = false;
    });
  }
};
</script>

<style>
.question-editor {
  resize: none;
}
.question-editor-preview {
  width: 100%;
  height: auto;
}
</style>
