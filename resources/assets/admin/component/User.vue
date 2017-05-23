<style lang="css" module>
.nav {
  padding: 12px 12px 0;
}
</style>

<template>
  <div>
    <ul class="nav nav-tabs" :class="$style.nav">
      <router-link to="/users" tag="li" active-class="active" exact>
        <a href="#">用户管理</a>
      </router-link>
      <router-link to="/users/setting" tag="li" active-class="active">
        <a href="#">基础设置</a>
      </router-link>
      <router-link to="/users/roles" tag="li" active-class="active">
        <a href="#">角色管理</a>
      </router-link>
      <router-link to="/users/permissions" tag="li" active-class="active">
        <a href="#">权限管理</a>
      </router-link>
    </ul>
    <router-view />
  </div>
</template>
