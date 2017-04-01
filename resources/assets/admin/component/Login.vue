<template>
  <div class="container">
    <div class="center-block login">
      <form role="form" @submit.prevent="submit">
        <div class="form-group">
          <label for="access">账户</label>
          <input type="tel" class="form-control" id="access" placeholder="输入账号" v-model="access">
        </div>
        <div class="form-group">
          <label for="password">密码</label>
          <input type="password" class="form-control" id="password" placeholder="输入密码" v-model="password">
        </div>
        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
        <button type="submit" class="btn btn-lg btn-primary btn-block">登录</button>
      </form>
    </div>
  </div>
</template>

<style lang="scss">
.login {
  width: 100%;
  min-width: 290px;
  max-width: 320px;
  margin-top: 40px;
  padding: 15px;
}
</style>

<script>
import { USER_UPDATE } from '../store/types';
import auth from '../util/auth';

const login = {
  data: () => ({
    access: '',
    password: '',
    error: null
  }),
  methods: {
    submit () {
      let { access, password } = this;
      this.error = null;
      auth.login(access, password)
      .then(response => {
        this.$store.dispatch(USER_UPDATE, cb => {
          cb(response.data);
          this.$router.replace(this.$route.query.redirect || '/');
        });
      })
      .catch(({ response: { data = {} } }) => {
        const { phone, password } = data;
        this.error = phone || password || '登录失败！';
      });
    }
  }
};

export default login;
</script>
