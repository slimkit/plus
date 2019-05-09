<template>
  <div class="about-us">
    <CommonHeader>{{ $t('setting.about.name') }}</CommonHeader>

    <main>
      <div
        class="content markdown-body"
        v-html="body"
      />
    </main>
  </div>
</template>

<script>
import * as api from '@/api'

export default {
  name: 'AboutUs',
  data () {
    return {
      body: '',
    }
  },
  created () {
    this.fetchAboutInfo()
  },
  methods: {
    fetchAboutInfo () {
      api.getAboutUs().then(({ data }) => {
        this.body = data
      })
    },
  },
}
</script>

<style lang='less' scoped>
.about-us {
  .content {
    padding: 20px;
    min-height: 100vh;
    background: #fff;
    line-height: 1.5;
  }
}
</style>

<style lang="less">
body {
  padding: 0 !important;
}
.about-us {

  .container .content {
    word-break: break-all;
  }
}
</style>
