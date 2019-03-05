<template>
  <div class="p-discover">
    <CommonHeader>
      发现
      <span slot="left" />
    </CommonHeader>

    <main>
      <ul
        v-for="(group, index) in entrys"
        :key="`${prefix}-entry-group-${index}`"
        class="m-entry-group padding"
      >
        <RouterLink
          v-for="{icon, title, path, tips, new_tips} in group"
          :key="`$discover-entry-${icon}`"
          :to="path"
          tag="li"
          class="m-entry"
        >
          <svg class="m-style-svg m-svg-def m-entry-prepend">
            <use :xlink:href="`#icon-discover-${icon}`" />
          </svg>
          <span class="m-flex-grow1">{{ title }}</span>
          <VBadge
            :dot="new_tips"
            class="m-entry-extra"
          >
            {{ tips }}
          </VBadge>
          <svg class="m-style-svg m-svg-def entry__item--append">
            <use xlink:href="#icon-arrow-right" />
          </svg>
        </RouterLink>
      </ul>
    </main>
    <FootGuide />
  </div>
</template>

<script>
export default {
  name: 'Discover',
  data () {
    return {
      prefix: 'discover',
      entrys: [
        [
          {
            title: '资讯',
            icon: 'news',
            path: '/news',
            new_tips: false,
            tips: '',
          },
          {
            title: '圈子',
            icon: 'group',
            path: '/group',
            new_tips: false,
            tips: '',
          },
          {
            title: '问答',
            icon: 'question',
            path: '/question',
            new_tips: false,
            tips: '',
          },
        ],
        [
          {
            title: '排行榜',
            icon: 'rank',
            path: '/rank',
            new_tips: false,
            tips: '',
          },
          {
            title: '话题',
            icon: 'topic',
            path: '/topic',
            new_tips: false,
            tips: '',
          },
        ],
        [
          {
            title: '找人',
            icon: 'find',
            path: '/find/pop',
            new_tips: false,
            tips: '',
          },
        ],
      ],
    }
  },
  methods: {
    to (path) {
      this.$router.push(path)
    },
  },
}
</script>

<style lang="less" scoped>
.p-discover {
  .m-entry-prepend {
    fill: currentColor;
  }
  .entry__item--append {
    fill: #999;
  }
}
</style>
