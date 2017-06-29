<template>
  <div>
    <ul class="nav nav-tabs component-controller-nav">
      <!-- Clients -->
      <router-link to="/oauth" tag="li" active-class="active" exact>
        <a href="#">客户端</a>
      </router-link>

      <!-- API Client setting -->
      <router-link to="/oauth/api-client" tag="li" active-class="active">
        <a href="#">API 客户端</a>
      </router-link>
    </ul>
    <router-view />
  </div>
</template>
