<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">

      <!-- 基础设置 -->
      <router-link to="/paid" tag="li" active-class="active" exact>
        <a href="#">基础设置</a>
      </router-link>

      <!-- 付费管理 -->
      <router-link to="/paid/manage" tag="li" active-class="active">
        <a>付费管理</a>
      </router-link>

    </ul>

    <div class="component-container container-fluid">
      <router-view />
    </div>

  </div>
</template>