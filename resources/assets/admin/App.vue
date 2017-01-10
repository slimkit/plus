<template>
  <div class="container" id="app">
    <h2>{{ message }}</h2>
    <input type="text" v-model="message">
  </div>
</template>

<script>
const App = {
  data () {
    return {
      message: 'init message.'
    };
  }
};

export default App;
</script>

<style lang="scss">
@import "../sass/bootstrap";
</style>
