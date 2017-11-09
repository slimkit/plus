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
export default {
  name: 'module-sensitive-list-item',
  props: {
    sensitive: { type: Object, required: true },
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
    submitChange ({ stopProcessing }) {},
    submitDelete ({ stopProcessing }) {},
  },
  created () {
    const { word, type, replace } = this.sensitive;
    this.form = { word, type, replace };
    console.log(this);
  }
};
</script>
