<template>
  <div id="app">
    <ui-alert></ui-alert>
    <router-view></router-view>
  </div>
</template>

<script>
import uiAlert from './components/modules/Alert';
export default { 
	name: 'app',
	components: { 'ui-alert': uiAlert },
};
</script>

<style>
#app {
  padding-top: 70px;
}
</style>