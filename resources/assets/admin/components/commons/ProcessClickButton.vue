<template>
  <button v-bind="$props" @click="handleClick" :disabled="processing">
    <slot :processing="processing" :stopProcessing="stopProcessing"></slot>
  </button>
</template>

<script>
export default {
  name: 'ui-process-button',
  data: () => ({
    processing: false,
  }),
  methods: {
    handleClick(event) {
      if (this.processing === true) {
        return;
      }

      event.stopProcessing = () => {
        this.stopProcessing();
      };

      this.processing = true;
      this.$emit('click', event);
    },

    stopProcessing() {
      this.processing = false;
    }
  }
}
</script>
