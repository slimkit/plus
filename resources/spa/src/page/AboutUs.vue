<template>
  <div class="about-us">

    <common-header>关于我们</common-header>

    <main>
      <div class="content markdown-body" v-html="body"/>
    </main>
  </div>
</template>

<script>
import md from "@/util/markdown";

export default {
  name: "AboutUs",
  computed: {
    aboutUs() {
      const { aboutUs = {} } = this.$store.state.CONFIG.site;
      return aboutUs;
    },
    body() {
      const content = this.aboutUs.content || "";
      return md(content);
    }
  }
};
</script>

<style lang='less' scoped>
.about-us {
  .content {
    padding: 20px;
    min-height: 100vh;
    background: #fff;
  }
}
</style>
