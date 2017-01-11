<template>
  <div v-show="show">haha</div>
</template>
<script>
import store from '../stores/alert';

const Alert = {
  computed: {
    show () {
      return store.state.show;
    }
  }
};

export default Alert;
</script>