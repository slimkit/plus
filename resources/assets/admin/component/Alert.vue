<style lang="scss" module>
.alert {
  margin: 22px 0 0;
}
</style>

<template>
  <div v-show="show" :class="['alert', `alert-${type}`, $style.alert]" role="alert">
    <slot />
  </div>
</template>

<script>
export default {
  props: {
    open: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      validator: value => ['success', 'info', 'warning', 'danger'].indexOf(value) !== -1,
      default: 'info',
    },
    class: [String, Array]
  },
  data: () => ({
    show: false
    interval: null
  }),
  watch: {
    open: function (open) {
      window.clearInterval(this.interval);
      if (open === true) {
        this.interval = window.setInterval(() => {
          this.open = false;
          window.clearInterval(this.interval);
        }, 1500);
      }
    }
  }
};
</script>
