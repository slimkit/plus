<template>
  <tr v-if="edit">
    <td>{{ form.id }}</td>
    <td>
      <input type="text" class="form-control" placeholder="请输入客户端名称" v-model="form.name">
    </td>
    <td>
      <input type="text" class="form-control" placeholder="请输入客户端地址" v-model="form.url">
    </td>
    <td>
      <input type="text" class="form-control" placeholder="请输入密钥" v-model="form.key">
    </td>
    <td>
      <bootstrap-ui-kit:ui-button type="button" class="btn btn-success btn-sm" proces-lable="修改中..." lable="保存" @click="handleUpdate"></bootstrap-ui-kit:ui-button>

      <button type="button" class="btn btn-default btn-sm" @click="handleSetEditMode(false)">取消</button>
    </td>
  </tr>
  <tr v-else-if="notHidden">
    <td>{{ form.id }}</td>
    <td>{{ form.name }}</td>
    <td>{{ form.url }}</td>
    <td>{{ form.key }}</td>
    <td>
      <button type="button" class="btn btn-primary btn-sm" @click="handleSetEditMode(true)">编辑</button>
      <bootstrap-ui-kit:ui-button type="button" class="btn btn-danger btn-sm" proces-lable="删除中..." lable="删除" @click="handleDestory"></bootstrap-ui-kit:ui-button>
    </td>
  </tr>
</template>

<script>
import { admin } from '../../axios';
export default {
  name: 'module-client-row',
  props: {
    client: { type: Object, required: true },
  },
  data: () => ({
    form: {},
    edit: false,
    hidden: false,
  }),
  computed: {
    notHidden () {
      return ! this.hidden;
    }
  },
  methods: {
    handleSetEditMode (mode) {
      this.edit = !! mode;
    },
    handleDestory ({ stopProcessing }) {
      admin.delete(`/clients/${this.client.id}`, {
        validateStatus: status => status === 204,
      }).then(() => {
        stopProcessing();
        this.hidden = true;
      }).catch(({ response: { data = { message: '删除失败!' } } = {} }) => {
        stopProcessing();
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
      });
    },
    handleUpdate ({ stopProcessing }) {
      admin.patch(`/clients/${this.client.id}`, this.form, {
        validateStatus: status => status === 204,
      }).then(() => {
        this.handleSetEditMode(false);
        stopProcessing();
      }).catch(({ response: { data = { message: '编辑失败!' } } = {} }) => {
        this.$store.dispatch('alert-open', { type: 'danger', message: data });
        stopProcessing();
      });
    }
  },
  created () {
    this.form = this.client;
  }
};
</script>
