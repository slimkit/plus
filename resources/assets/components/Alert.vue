<template>
  <div v-show="show">{{ message }}</div>
</template>
<script>
import store from '../stores/alert';

const Alert = {
  computed: {
    show () {
      return store.state.show;
    },
    message: () => store.state.message
  }
};

export default Alert;
</script>