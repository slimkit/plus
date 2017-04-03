<template>
  <div class="component-container container-fluid">
    <button type="button" class="btn btn-default" @click="goBack">返回</button>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>
            <input type="checkbox" v-model="checkBoxSelectAll" />
          </th>
          <th>节点名称</th>
          <th>显示名称</th>
          <th>描述</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="perm in perms" @key="perm.id">
          <th>
            <input type="checkbox" :value="perm.id" v-model="seleced" />
          </th>
          <td>{{ perm.name }}</td>
          <td>{{ perm.display_name }}</td>
          <td>{{ perm.description }}</td>
        </tr>
      </tbody>
    </table>

    <div v-show="loadding" class="component-loadding">
      <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
    </div>

    <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" @click.prevent="dismisError">
        <span aria-hidden="true">&times;</span>
      </button>
      {{ error }}
    </div>

    <button v-if="submit" type="button" class="btn btn-primary" disabled="disabled">
      <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
    </button>
    <button v-else type="button" class="btn btn-primary" @click="postPerms">提交</button>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import lodash from 'lodash';

const RoleManageComponent = {
  data: () => ({
    perms: [],
    seleced: [],
    role: {},
    loadding: false,
    submit: false,
    error: null
  }),
  computed: {
    checkBoxSelectAll: {
      get () {
        return this.perms.length === this.seleced.length;
      },
      set (value) {
        if (value === false) {
          this.seleced = [];
          return;
        }

        let seleced = [];
        this.perms.forEach(perm => seleced.push(perm.id));
        this.seleced = seleced;
      }
    }
  },
  methods: {
    postPerms () {
      const seleced = this.seleced;
      const { id } = this.role;
      this.submit = true;
      request.patch(
        createRequestURI(`roles/${id}`),
        { perms: seleced },
        { validateStatus: status => status === 201 }
      ).then(() => {
        this.submit = false;
      }).catch(({ response: { data: { errors = ['更新失败'] } = {} } = {} }) => {
        this.submit = false;
        this.error = lodash.values(errors).pop();
      });
    },
    goBack () {
      this.$router.back();
    },
    dismisError () {
      this.error = null;
    }
  },
  created () {
    const { params: { role: id } } = this.$route;
    this.loadding = true;
    request.get(
      createRequestURI(`roles/${id}`),
      {
        params: {
          all_perms: true,
          perms: true
        },
        validateStatus: status => status === 200
      }
    ).then(({ data }) => {
      const { perms, role } = data;
      this.perms = perms;
      this.role = role;

      let seleced = [];
      role.perms.forEach(perm => seleced.push(perm.id));
      this.seleced = seleced;
      this.loadding = false;
    }).catch(({ response: { data: { errors = ['加载失败，请刷新重试！'] } = {} } = {} }) => {
      this.loadding = false;
      this.error = lodash.values(errors).pop();
    });
  }
};

export default RoleManageComponent;
</script>
