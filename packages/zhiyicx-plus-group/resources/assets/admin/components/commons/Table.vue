<style lang="css" module>
.loadding {
    text-align: center;
    font-size: 42px;
}
.loaddingIcon {
    animation-name: TurnAround;
    animation-duration: 3s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
}

@keyframes TurnAround {
  from {transform: rotate(0deg);}
  to { transform: rotate(360deg); }
}
</style>
<template>
<div>
  <table class="table table-bordered">
    <!-- table-thead -->
    <thead> 
      <slot name="table-thead-tr"></slot> 
    </thead>
    <tbody>
      <!-- table-loading -->
      <tr v-show="loading">
          <!-- 加载动画 -->
          <td :class="$style.loadding" :colspan="colspanNum">
              <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
          </td>
      </tr>
      <!-- table-tbody -->
      <slot name="table-tbody-tr"></slot>
      <!-- table-other -->
      <slot name="table-tbody-other"></slot>

    </tbody>
  </table>
</div>
</template>
<script>
export default {
    props: {
       loading: {
         type: Boolean,
         required: true,
       },
        colspanNum: {
          type: Number,
          required: true,
        }
    },
};
</script>