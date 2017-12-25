<template>
  <div class="alert alert-dismissible" :class="`alert-${type}`" role="alert">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'ui-alert',
  props: {
    type: String
  }
};
</script>
