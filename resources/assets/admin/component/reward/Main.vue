<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <!-- 广告 -->
      <router-link to="/reward" tag="li" active-class="active" exact>
        <a href="#">打赏统计</a>
      </router-link>
      <router-link to="/reward/list" tag="li" active-class="active">
        <a href="#">打赏清单</a>
      </router-link>
    </ul>
    <router-view />
  </div>
</template>