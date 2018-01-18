<template>
  <tr>
    <td>{{ sensitive.id }}</td>
    <td>
      <input :disabled="submitting" type="text" class="form-control" placeholder="请输入敏感词" v-model="form.word">
    </td>
    <td>
      <select class="form-control" v-model="form.type" :disabled="submitting">
        <option value="warning">提示</option>
        <option value="replace">替换</option>
      </select>
    </td>
    <td>
      <input :disabled="submitting" v-show="isReplace" type="text" class="form-control" placeholder="请输入替换词" v-model="form.replace">
    </td>
    <td>
      <ui-button type="button" class="btn btn-info" proces-lable="修改中..." @click="submitChange">修改</ui-button>
      <ui-button type="button" class="btn btn-danger" proces-lable="删除中..." @click="submitDelete">删除</ui-button>
    </td>
  </tr>
</template>

<script>
import request, { createRequestURI } from '../../../util/request';
import { plusMessageFirst } from '../../../filters';
export default {
  name: 'module-sensitive-list-item',
  props: {
    sensitive: { type: Object, required: true },
    handleChange: { type: Function, required: true },
    handleDelete: { type: Function, required: true },
  },
  data: () => ({
    form: {
      word: null,
      type: null,
      replace: null
    },
    submitting: false,
  }),
  computed: {
    isReplace () {
      const { type } = this.form;

      return type === 'replace';
    }
  },
  methods: {
    submitChange ({ stopProcessing }) {

      if (this.submitting === true) {
        alert('正在等待其他操作完成！');
        stopProcessing();

        return ;
      }

      this.submitting = true;
      request.patch(createRequestURI(`sensitives/${this.sensitive.id}`), this.form, {
        validateStatus: status => status === 201,
      }).then(({ data: { sensitive } }) => {
        this.submitting = false;
        stopProcessing();
        this.handleChange(sensitive);
      }).catch(({ response: { data } = {} }) => {
        this.submitting = false;
        stopProcessing();
        alert(plusMessageFirst(data, '更新失败！'));
      });
    },
    submitDelete ({ stopProcessing }) {
      
      if (this.submitting === true) {
        alert('正在等待其他操作完成！');
        stopProcessing();

        return ;
      }

      this.submitting = true;
      request.delete(createRequestURI(`sensitives/${this.sensitive.id}`), {
        validateStatus: status => status === 204
      }).then(() => {
        this.submitting = false;
        this.handleDelete(this.sensitive.id);
      }).catch(({ response: { data } = {} }) => {
        this.submitting = false;
        stopProcessing();
        alert(plusMessageFirst(data, '删除失败！'));
      });
    },
  },
  created () {
    const { word, type, replace } = this.sensitive;
    this.form = { word, type, replace };
  }
};
</script>
