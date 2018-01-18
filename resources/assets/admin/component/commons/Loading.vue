<template>
      <!-- Loading -->
      <div v-show="loadding" class="panel-body text-center">
        <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
        加载中...
      </div>
</template>
<script>
export default {
    props: {
       loadding: {
         type: Boolean,
         required: true,
       },
    },
};
</script>