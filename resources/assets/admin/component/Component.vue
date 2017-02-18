<style>
.app-iframe {
  position: absolute;
  top: 0;
  height: 100%;
  max-height: 100%;
  overflow: auto;
  border: none;
}
</style>

<template>
  <iframe class="app-iframe" :src="uri"></iframe>
</template>

<script>
const menu = window.TS.menu;

const component = {
  data: () => ({...menu}),
  computed: {
    uri () {
      const { component } = this.$route.params;

      if (!this[component]) {
        this.$router.replace('/');
        return;
      }

      return this[component].admin;
    }
  }
};

export default component;
</script>
