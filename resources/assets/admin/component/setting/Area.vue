<style lang="css" module>
.container {
  padding-top: 15px;
}
</style>

<template>
  <div class="container-fluid" :class="$style.container">
    <ol class="breadcrumb">
      <li><a href="#">国家</a></li>
      <li class="active">中国</li>
    </ol>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import { SETTINGS_AREA } from '../../store/getter-types';

const AreaComponent = {
  data: () => ({
    // list: []
  }),
  computed: {
    ...mapGetters({
      areas: SETTINGS_AREA
    }),
    list () {
      return [];
    }
  },
  created () {
    const { id = 0 } = this.$route.params;
    console.log(id);
  }
};

export default AreaComponent;
</script>
