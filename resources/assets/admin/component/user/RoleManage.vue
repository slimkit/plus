<template>
  <div class="component-container container-fluid">
    <button type="button" class="btn btn-default">返回</button>
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
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const RoleManageComponent = {
  data: () => ({
    perms: [],
    seleced: [],
    role: {}
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
  created () {
    const { params: { role: id } } = this.$route;
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
    }).catch(() => {
      window.alert('加载失败，请刷新重试！');
    });
  }
};

export default RoleManageComponent;
</script>
