<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <!-- 广告 -->
      <router-link to="/ad" tag="li" active-class="active">
        <a href="#">广告管理</a>
      </router-link>
    </ul>
    <router-view />
  </div>
</template>