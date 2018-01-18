<template>
  <div class="container">
    <ui-nav-bar></ui-nav-bar>
    <module-alert></module-alert>
    <router-view></router-view>
  </div>
</template>

<script>
import Alert from '../modules/Alert';
export default {
  name: 'main',
  components: {
    [Alert.name]: Alert,
  },
};
</script>
