<style lang="scss">
.app-nav {
  background-color: #37474f;
  width: 100%;
  min-height: 100%;
  padding-top: 16px;
  margin: 0;
  .__button {
    background: none;
    border: none;
    color: #a6aeb2;
    color: rgba(255, 255, 255, 0.56);
    padding: 16px 40px;
    font-weight: 500;
    &:last-child,
    &:first-child {
      border-radius: 0;
    }
    &:focus {
      outline: none;
      background: none;
      color: #a6aeb2;
      color: rgba(255, 255, 255, 0.56);
    }

    &.active {
      border-right-width: 4px;
      border-right-style: solid;
      border-right-color: #3097D1;
      color: #fff;
      background: #000;
    }

    &:hover {
      background: #263238;
      color: #fff;
    }
    .__icon {
      margin-right: 32px;
      color: #78909c;
    }
    .__icon-img {
      width: 14px;
      height: 14px;
      border: none;
      margin-right: 32px;
    }
  }
}
</style>

<template>
  <div class="list-group app-nav">
    <router-link class="list-group-item __button" to="/setting" active-class="active" >
      <span class="glyphicon glyphicon-cog __icon"></span>
      系统设置
    </router-link>
    <router-link class="list-group-item __button" to="/users" active-class="active" >
      <span class="glyphicon glyphicon-user __icon"></span>
      用户中心
    </router-link>
    <router-link class="list-group-item __button" to="/sms" active-class="active" >
      <span class="glyphicon glyphicon-phone __icon"></span>
      短信设置
    </router-link>
    <router-link class="list-group-item __button" to="/wallet" active-class="active" >
      <span class="glyphicon glyphicon-credit-card __icon"></span>
      钱包
    </router-link>
    <router-link class="list-group-item __button" v-for="({ name, icon }, component) in menus" :key="component" :to="`/component/${component}`" active-class="active" exact>
      <img class="__icon-img" :src="icon">
      {{ name }}
    </router-link>
  </div>
</template>

<script>
const menus = window.TS.menus || {};
const nav = {
  data: () => ({
    menus
  })
};

export default nav;
</script>
