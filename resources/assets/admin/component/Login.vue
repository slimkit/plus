<template>
  <div class="container">
    <div class="center-block login">
      <form role="form">
        <div class="form-group">
          <label for="access">账户</label>
          <input type="tel" class="form-control" id="access" placeholder="输入账号">
        </div>
        <div class="form-group">
          <label for="password">密码</label>
          <input type="password" class="form-control" id="password" placeholder="输入密码">
        </div>
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
