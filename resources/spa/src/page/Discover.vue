<template>
  <div class="p-discover">
    <CommonHeader>
      {{ $t('discover') }}
      <span slot="left" />
    </CommonHeader>

    <main>
      <ul
        v-for="(group, index) in entrys"
        :key="`${prefix}-entry-group-${index}`"
        class="m-entry-group padding"
      >
        <RouterLink
          v-for="item in group"
          :key="`$discover-entry-${item.icon}`"
          :to="item.path"
          tag="li"
          class="m-entry"
        >
          <svg class="m-style-svg m-svg-def m-entry-prepend">
            <use :xlink:href="`#icon-discover-${item.icon}`" />
          </svg>
          <span class="m-flex-grow1">{{ item.title }}</span>
          <BadgeIcon :dot="item.new_tips" class="m-entry-extra">
            {{ item.tips }}
          </BadgeIcon>
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
import i18n from '@/i18n'

const entrys = [
  [
    {
      title: i18n.t('news.name'),
      icon: 'news',
      path: '/news',
      new_tips: false,
      tips: '',
    },
    {
      title: i18n.t('group.name'),
      icon: 'group',
      path: '/group',
      new_tips: false,
      tips: '',
    },
    {
      title: i18n.t('question.qa'),
      icon: 'question',
      path: '/question',
      new_tips: false,
      tips: '',
    },
  ],
  [
    {
      title: i18n.t('rank.name'),
      icon: 'rank',
      path: '/rank',
      new_tips: false,
      tips: '',
    },
    {
      title: i18n.t('feed.topic.name'),
      icon: 'topic',
      path: '/topic',
      new_tips: false,
      tips: '',
    },
  ],
  [
    {
      title: i18n.t('find.name'),
      icon: 'find',
      path: '/find/pop',
      new_tips: false,
      tips: '',
    },
  ],
]

export default {
  name: 'Discover',
  data () {
    return {
      prefix: 'discover',
      entrys,
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
