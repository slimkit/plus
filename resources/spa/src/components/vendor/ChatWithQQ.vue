<template>
  <div class="c-chat-with-qq" @click="onChatClick">
    <img :src="require('@/images/ico_consult.png')" alt="">
    <span>{{ label }}</span>
  </div>
</template>

<script>
export default {
  name: 'ChatWithQQ',
  data () {
    return {
      label: process.env.VUE_APP_QQ_CONSULT_LABEL || '在线咨询',
    }
  },
  methods: {
    onChatClick () {
      const uin = process.env.VUE_APP_QQ_CONSULT_UIN
      if (!uin) return this.$Message.error('没有配置正确的UIN')
      window.open(`//wpa.qq.com/msgrd?v=3&uin=${uin}&site=qq&menu=yes`)
    },
  },
}
</script>

<style lang="less" scoped>
@margin: 20px;
@width: 90px;

.c-chat-with-qq {
  position: fixed;
  right: @margin;
  bottom: @margin+100px;
  display: flex;
  flex-direction: column;
  width: @width;
  border-radius: 6px;/*no*/
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.09); /*no*/
  background-color: rgba(255,255,255,.9);
  overflow: hidden;

  img {
    width: @width;
    height: @width;
  }

  span {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 2.3*@width;
    writing-mode: vertical-lr;
    letter-spacing: 4px;/*no*/
  }
}
</style>
