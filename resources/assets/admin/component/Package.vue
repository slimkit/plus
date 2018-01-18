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
import { MANAGES_GET } from '../store/getter-types';
import { mapGetters } from 'vuex';
export default {
  computed: {
    ...mapGetters({
      manages: MANAGES_GET
    }),
    uri() {
      const { key } = this.$route.params;
      const { [key]: { uri } = {} } = this.manages;

      return uri;
    }
  }
};
</script>
