<template>
  <Transition
    enter-active-class="animated bounceInRight"
    leave-active-class="animated bounceOutRight"
  >
    <div class="protocol">
      <CommonHeader>用户注册协议</CommonHeader>

      <main>
        <div
          class="content"
          v-html="content"
        />
      </main>
    </div>
  </Transition>
</template>

<script>
import markdownIt from 'markdown-it'

// TODO: 注册页面和协议页面切换时保留过渡动画

export default {
  name: 'RegisterProtocol',
  computed: {
    content () {
      let content = this.$store.state.CONFIG.registerSettings.content
      content = content.replace(/<br>/, '\n')
      return markdownIt({ html: true }).render(content)
    },
  },
}
</script>

<style lang="less" scoped>
.protocol {
  .content {
    padding: 20px;
    > * {
      margin-bottom: 0.4rem;
    }
  }
  h1 {
    font-size: 0.62rem;
    font-weight: bold;
  }
  h4 {
    font-weight: bold;
  }
  ol {
    list-style: decimal outside;
    margin-left: 1em;
  }
  blockquote {
    margin-left: 1em;
  }
}
</style>
