<style lang="css" module>
.roleItem {
  margin-right: 10px;
}
</style>

<template>
  <div class="component-container container-fluid form-horizontal">
     <!-- user name -->
      <div class="form-group">
        <label for="name" class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="name" aria-describedby="name-help-block" placeholder="请输入用户名" v-model="user.name">
        </div>
        <span class="col-sm-4 help-block" id="name-help-block">
          请输入用户名，只能以非特殊字符和数字抬头！
        </span>
      </div>

      <!-- phone -->
      <div class="form-group">
        <label for="phone" class="col-sm-2 control-label">手机号码</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="phonepassword" aria-describedby="phone-help-block" placeholder="请输入手机号码" v-model="user.phone">
        </div>
        <span class="col-sm-4 help-block" id="phone-help-block">
          手机号码
        </span>
      </div>

      <!-- email -->
      <div class="form-group">
        <label for="email" class="col-sm-2 control-label">电子邮件</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="phonepassword" aria-describedby="email-help-block" placeholder="请输入邮箱地址" v-model="user.email">
        </div>
        <span class="col-sm-4 help-block" id="email-help-block">
          电子邮箱
        </span>
      </div>

      <!-- password -->
      <div class="form-group">
        <label for="password" class="col-sm-2 control-label">新密码</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="password" aria-describedby="password-help-block" placeholder="请输入新的用户密码" v-model="password">
        </div>
        <span class="col-sm-4 help-block" id="password-help-block">
          输入新密码，并提交后会改变当前用户的密码，留空则表示不变更。
        </span>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">角色</label>
        <div class="col-sm-6">
          <label v-for="role in roles" :key="role.id" :class="$style.roleItem">
            <input type="checkbox" :value="role.id" v-model="selecedRoles" /> {{ role.display_name }}
          </label>
        </div>
        <span class="col-sm-4 help-block" id="role-help-block">
          选择用户拥有的角色
        </span>
      </div>

      <!-- Button -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button v-if="changeIn" type="button" class="btn btn-primary" disabled="disabled">
            <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
          </button>
          <button v-else type="button" class="btn btn-primary" @click="updateUser">修改资料</button>
        </div>
      </div>

      <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" @click.prevent="dismisError">
          <span aria-hidden="true">&times;</span>
        </button>
        {{ error }}
      </div>

  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const UserManageComponent = {
  data: () => ({
    changeIn: false,
    password: '',
    error: null,
    user: {},
    roles: [],
    selecedRoles: []
  }),
  methods: {
    updateUser () {
      this.changeIn = true;
      const { id, name, phone, email } = this.user;
      const roles = this.selecedRoles;
      const password = this.password;
      request.patch(
        createRequestURI(`users/${id}`),
        { name, phone, email, password, roles },
        { validateStatus: status => status === 201 }
      ).then(() => {
        this.changeIn = false;
      }).catch(({ response: { data = {} } = {} }) => {
        const { phone = [], name = [], email = [], roles = [], message = [] } = data;
        const [ errorMessage ] = [ ...phone, ...name, ...email, ...roles, ...message ];

        this.error = errorMessage;
        this.changeIn = false;
      });
    },
    dismisError () {
      this.error = null;
    }
  },
  created () {
    const { params: { userId } } = this.$route;
    request.get(
      createRequestURI(`users/${userId}`),
      {
        params: { show_role: 1 },
        validateStatus: status => status === 200
      }
    ).then(({ data: { user, roles } }) => {
      this.user = user;
      this.roles = roles;

      let selecedRoles = [];
      user.roles.forEach(role => selecedRoles.push(role.id));
      this.selecedRoles = selecedRoles;
    }).catch(({ response: { data: { errors = [] } = {} } = {} }) => {
      const [ errorMessage = '获取失败，请刷新重试！' ] = errors;
      this.error = errorMessage;
    });
  }
};

export default UserManageComponent;
</script>
