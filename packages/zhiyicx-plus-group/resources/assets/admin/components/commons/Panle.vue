<template>
<div class="panel panel-default">
  <div class="panel-heading">
  	{{ title }}
  	<slot name="slot-panel-heading"></slot>
  </div>
  <div class="panel-body">
  	<slot></slot>
  </div>
</div>
</template>
<script>
export default ({
  name: 'ui-panle',
  props: {
    title: { type: String, required: true },
  }
});
</script>