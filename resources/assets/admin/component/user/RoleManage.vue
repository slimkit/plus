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

    <button v-if="submit" type="button" class="btn btn-primary" disabled="disabled">
      <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
    </button>
    <button v-else type="button" class="btn btn-primary" @click="postPerms">提交</button>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const RoleManageComponent = {
  data: () => ({
    perms: [],
    seleced: [],
    role: {},
    loadding: false,
    submit: false
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
      }).catch(() => {
        this.submit = false;
        window.alert('更新失败');
      });
    },
    goBack () {
      this.$router.back();
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
    }).catch(() => {
      window.alert('加载失败，请刷新重试！');
    });
  }
};

export default RoleManageComponent;
</script>
