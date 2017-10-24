<template>
  <ui-process-button v-bind="$props" @click="handleClick">
    <template scope="{ processing }">
      <template v-if="processing">
        <ui-loadding></ui-loadding>
        {{ procesLable }}
      </template>
      <template v-else>
        {{ lable }}
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
