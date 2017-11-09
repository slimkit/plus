<template>
  <tr>
    <td></td>
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
      <ui-button type="button" class="btn btn-primary" proces-lable="添加中..." @click="handleStore">添加</ui-button>
    </td>
  </tr>
</template>

<script>
import request, { createRequestURI } from '../../../util/request';
import { plusMessageFirst } from '../../../filters';
export default {
  name: 'module-sensitive-store',
  props: {
    handleAppend: { type: Function, required: true },
  },
  data: () => ({
    form: {
      word: null,
      type: 'warning',
      replace: null,
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
    handleStore ({ stopProcessing }) {
      this.submitting = true;

      request.post(createRequestURI('sensitives'), this.form, {
        validateStatus: status => status === 201,
      }).then(({ data: { sensitive } }) => {
        this.handleAppend(sensitive);
        this.form = { word: null, type: 'warning', replace: null };
        stopProcessing();
        this.submitting = false;
      }).catch(({ response: { data } = {} }) => {
        alert(plusMessageFirst(data, '添加失败！'));
        stopProcessing();
        this.submitting = false;
      });
    }
  },
};
</script>
