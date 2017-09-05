<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <router-link to="/sms" tag="li" active-class="active" exact>
        <a href="#">网关设置</a>
      </router-link>
      <router-link to="/sms/alidayu" tag="li" active-class="active">
        <a href="#">阿里大于</a>
      </router-link>
      <router-link to="/sms/aliyun" tag="li" active-class="active">
        <a href="#">阿里云</a>
      </router-link>
      <router-link to="/sms/yunpian" tag="li" active-class="active">
        <a href="#">云片</a>
      </router-link>
    </ul>
    <router-view />
  </div>
</template>