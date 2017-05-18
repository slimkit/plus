<template>
  <div class="component-container container-fluid">
    <div class="form-horizontal">
      <!-- user name -->
      <div class="form-group">
        <label for="name" class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="name" aria-describedby="name-help-block" placeholder="请输入用户名" v-model="name">
        </div>
        <span class="col-sm-4 help-block" id="name-help-block">
          请输入用户名，只能以非特殊字符和数字抬头！
        </span>
      </div>

      <!-- phone -->
      <div class="form-group">
        <label for="phone" class="col-sm-2 control-label">手机号码</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="phonepassword" aria-describedby="phone-help-block" placeholder="请输入手机号码" v-model="phone">
        </div>
        <span class="col-sm-4 help-block" id="phone-help-block">
          手机号码
        </span>
      </div>

      <!-- password -->
      <div class="form-group">
        <label for="password" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-6">
          <input type="password" class="form-control" id="password" aria-describedby="password-help-block" placeholder="请输入用户密码" v-model="password">
        </div>
        <span class="col-sm-4 help-block" id="password-help-block">
          用户密码
        </span>
      </div>

      <!-- Button -->
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button v-if="adding" type="button" class="btn btn-primary" disabled="disabled">
            <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
          </button>
          <button v-else type="button" class="btn btn-primary" @click="createUser">添加用户</button>
        </div>
      </div>

      <div v-show="errorMessage" class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" @click.prevent="dismisError">
          <span aria-hidden="true">&times;</span>
        </button>
        {{ errorMessage }}
      </div>

    </div>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';

const UserAddComponent = {
  data: () => ({
    name: '',
    phone: '',
    password: '',
    adding: false,
    errorMessage: ''
  }),
  methods: {
    createUser () {
      this.adding = true;
      request.post(
        createRequestURI('users'),
        { name: this.name, phone: this.phone, password: this.password },
        { validateStatus: status => status === 201 }
      ).then(({ data: { user_id: userId } }) => {
        this.$router.replace({ path: '/users/manage/'+userId });
      }).catch(({ response: { data = {} } = {} }) => {
        const { name = [], phone = [], password = [], message = [] } = data;
        const [ errorMessage ] = [ ...name, ...phone, ...password, ...message ];

        this.errorMessage = errorMessage;
        this.adding = false;
      });
    },
    dismisError () {
      this.errorMessage = '';
    }
  }
};

export default UserAddComponent;
</script>
