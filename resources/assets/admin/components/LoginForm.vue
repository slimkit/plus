<template>
  <div class="container" id="app">
    <form class="form-signin" role="form" @submit.prevent="onSubmit">
      <h2 class="form-signin-heading text-center">后台</h2>

      <input type="tel" class="form-control" placeholder="输入手机号码" required autofocus v-model="phone">
      <input type="password" class="form-control" placeholder="输入密码" required v-model="password">

      <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
    </form>
  </div>
</template>

<script>
const LoginForm = {
  data () {
    return {
      phone: '',
      password: ''
    };
  },

  methods: {
    onSubmit () {
      const phone = this.phone;
      const password = this.password;
      console.log(phone, password);
    }
  }
};

export default LoginForm;
</script>

<style lang="scss">
@import "../../sass/bootstrap";

body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;

  .form-signin-heading {
    margin-bottom: 10px;
  }

  .form-control {
    position: relative;
    height: auto;
    -webkit-box-sizing: border-box;
       -moz-box-sizing: border-box;
            box-sizing: border-box;
    padding: 10px;
    font-size: 16px;
  }

  .form-control:focus {
    z-index: 2;
  }

  input[type="tel"] {
    margin-bottom: -1px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
  }

  input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }

}
</style>
