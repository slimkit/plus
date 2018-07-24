<style>
  .alert-style {
    position:fixed;
    top:0;
    width:100%;
    z-index:9999;
    border-radius: 0;
  }
</style>
<template>
   <div class="alert alert-style" :class="type" role="alert" v-show="open">
     {{ message | plusMessageFirst(defaultMessage) }}
   </div>
</template>

<script>
import { mapState } from 'vuex';
export default {
  name: 'module-alert',
  computed: {
    ...mapState({
      alert (state) {
        const { alert = {} } = state;

        return alert;
      },
    }),
    open () {
      const { open } = this.alert;

      return !! open;
    },
    message () {
      const { message } = this.alert;

      return message;
    },
    type () {
      const { type } = this.alert;

      return `alert-${type}`;
    },
    defaultMessage() {
      const { defaultMessage } = this.alert;
      return defaultMessage;
    },
  },
};
</script>
