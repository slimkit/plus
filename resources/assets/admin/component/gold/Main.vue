<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <!-- 广告 -->
      <router-link to="/gold" tag="li" active-class="active" exact>
        <a href="#">金币类型</a>
      </router-link>
      <router-link to="/gold/rules" tag="li" active-class="active">
        <a href="#">金币规则</a>
      </router-link>
    </ul>
    <router-view />
  </div>
</template>