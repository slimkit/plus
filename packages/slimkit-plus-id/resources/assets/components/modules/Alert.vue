<template>
   <div class="alert" :class="type" role="alert" v-show="open">
     {{ message | plusMessageFirst('操作失败') }}
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
  },
};
</script>
