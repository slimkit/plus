<style lang="css" module>
.appIframe {
  position: absolute;
  top: 0;
  height: 100%;
  max-height: 100%;
  overflow: auto;
  border: none;
}
</style>

<template>
  <iframe :class="$style.appIframe" :src="uri"></iframe>
</template>

<script>
const menus = window.TS.menus || {};

const component = {
  data: () => ({...menus}),
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
