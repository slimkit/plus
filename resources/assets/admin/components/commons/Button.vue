<template>
  <ui-process-button v-bind="$props" @click="handleClick">
    <template slot-scope="{ processing }">
      <template v-if="processing">
        <ui-loading></ui-loading>
        {{ procesLable }}
      </template>
      <template v-else>
        <slot>{{ lable }}</slot>
      </template>
    </template>
  </ui-process-button>
</template>

<script>
export default {
  name:'ui-button',
  props: {
    lable: { type: String, default: '提交' },
    procesLable: { type: String, default: '提交中...' },
  },
  methods: {
    handleClick (event) {
      this.$emit('click', event);
    }
  },
};
</script>
