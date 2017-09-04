<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <router-link to="/captcha" tag="li" active-class="active" exact>
        <a href="#">验证码记录</a>
      </router-link>
      <router-link to="/captcha/gatway" tag="li" active-class="active">
        <a href="#">网关设置</a>
      </router-link>
    </ul>
    <router-view />
  </div>
</template>