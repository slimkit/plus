<template>
  <div id="app">
    {{ message }}
  </div>
</template>

<script>

const App = {
  data() {
    return {
      message: 123,
    }
  }
};

export default App;

</script>

<style>
  #app {
    width: 100%;
    text-align: center;
  }
</style>
