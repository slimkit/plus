<style lang="css" module>
    .loadding {
        text-align: center;
        font-size: 42px;
    }
    .loaddingIcon {
        animation-name: "TurnAround";
        animation-duration: 1.4s;
        animation-timing-function: linear;
        animation-iteration-count: infinite;
    }
</style>

<template>
<tr v-show="loadding">
    <!-- 加载动画 -->
    <td :class="$style.loadding" :colspan="colspanNum">
        <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
    </td>
</tr>
</template>
<script>
export default {
    props: {
       loadding: {
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
