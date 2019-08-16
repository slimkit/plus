<template>
  <div class="container-fluid" style="margin-top:10px;">
    <div class="panel panel-default form-horizontal">
      <div class="panel-heading">
        用户添加
        <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/users" role="button">
          返回
        </router-link>
      </div>
      <div class="panel-body">
        <!-- user name -->
        <div class="form-group">
          <label for="name" class="col-sm-2 control-label">用户名</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="name" aria-describedby="name-help-block" placeholder="请输入用户名" v-model="name">
          </div>
          <span class="col-sm-4 help-block" id="name-help-block">
            请输入用户名，只能以非特殊字符和数字开头！
          </span>
        </div>

        <!-- phone -->
        <div class="form-group">
          <label for="phone" class="col-sm-2 control-label">手机号码</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="phone" aria-describedby="phone-help-block" placeholder="请输入手机号码" v-model="phone">
          </div>
          <span class="col-sm-4 help-block" id="phone-help-block">
            可选，手机号码
          </span>
        </div>

        <!-- email -->
        <div class="form-group">
          <label for="email" class="col-sm-2 control-label">邮箱</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="email" aria-describedby="phone-help-block" placeholder="请输入邮箱地址" v-model="email">
          </div>
          <span class="col-sm-4 help-block" id="email-help-block">
            可选，电子邮箱
          </span>
        </div>

        <!-- password -->
        <div class="form-group">
          <label for="password" class="col-sm-2 control-label">密码</label>
          <div class="col-sm-6">
            <input type="password" minlength="6" autocomplete="new-password" class="form-control" id="password" aria-describedby="password-help-block" placeholder="请输入用户密码" v-model="password">
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
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import plusMessageBundle from 'plus-message-bundle';

const UserAddComponent = {
  data: () => ({
    name: '',
    phone: '',
    email: '',
    password: '',
    adding: false,
    errorMessage: ''
  }),
  methods: {
    createUser () {
      if (this.password.length < 6) {
        this.errorMessage = '密码长度不能小于6位'
        return;
      }
      this.adding = true;
      request.post(
        createRequestURI('users'),
        { name: this.name, phone: this.phone, email: this.email, password: this.password },
        { validateStatus: status => status === 201 }
      ).then(({ data: { user_id: userId } }) => {
        this.$router.replace({ path: '/users/manage/'+userId });
      }).catch(({ response: { data = {} } = {} }) => {
        this.adding = false;
        let Message = new plusMessageBundle(data);
        this.errorMessage = Message.getMessage();
      });
    },
    dismisError () {
      this.errorMessage = '';
    }
  }
};

export default UserAddComponent;
</script>
