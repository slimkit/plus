<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <router-link to="/sms" tag="li" active-class="active" exact>
        <a href="#">验证码记录</a>
      </router-link>
      <router-link to="/sms/driver" tag="li" active-class="active">
        <a href="#">短信驱动</a>
      </router-link>
      <router-link to="/sms/alidayu" tag="li" active-class="active">
        <a href="#">阿里大于</a>
      </router-link>
    </ul>
    <router-view />
  </div>
</template>