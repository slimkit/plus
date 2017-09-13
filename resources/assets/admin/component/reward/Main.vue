<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <!-- 广告 -->
      <router-link to="/reward" tag="li" active-class="active">
        <a href="#">数据展示</a>
      </router-link>
    </ul>
    <router-view />
  </div>
</template>