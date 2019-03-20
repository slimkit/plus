<template id="alert-template">
  <div
    v-show="show"
    :class="alertClasses"
  >
    <slot>
      <strong>Default!</strong> Hello World~
    </slot>
    <span
      class="Alert__close"
      @click="show = false"
    >
      x
    </span>
  </div>
</template>
<script>
export default {
  name: 'Message',
  props: {
    type: { type: String, default: '' },
  },
  data () {
    return {
      show: true,
    }
  },
  computed: {
    alertClasses () {
      return {
        'Alert--Success': this.type === 'success',
        'Alert--Warning': this.type === 'warning',
        'Alert--Error': this.type === 'error',
      }
    },
  },
}
</script>
<style>
.Alert__close {
  font-weight: bold;
  cursor: pointer;
}

.Alert--Success {
  color: green;
}

.Alert--Warning {
  color: #aa0;
}

.Alert--Error {
  color: red;
}
</style>
