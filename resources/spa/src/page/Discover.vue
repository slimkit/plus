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
      <ul v-if="SHOP_PLUS_ID" class="m-entry-group padding">
        <li class="m-entry" @click="goShop">
          <svg class="m-style-svg m-svg-def m-entry-prepend">
            <use :xlink:href="`#icon-discover-find`" />
          </svg>
          <span class="m-flex-grow1">商城</span>
          <BadgeIcon :dot="false" class="m-entry-extra"></BadgeIcon>
          <svg class="m-style-svg m-svg-def entry__item--append">
            <use xlink:href="#icon-arrow-right" />
          </svg>
        </li>
      </ul>
    </main>
    <FootGuide />
  </div>
</template>

<script>
import i18n from '@/i18n'
import * as api from '@/api'

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
      SHOP_PLUS_ID: process.env.VUE_APP_SHOP_PLUS_ID || 0,
    }
  },
  methods: {
    to (path) {
      this.$router.push(path)
    },
    goShop () {
      api.getShop(this.SHOP_PLUS_ID).then(({ data }) => {
        let params = data.params || []
        let url = data.url || ''
        params = Object.keys(params).map(function (key) {
          return encodeURIComponent(key) + '=' + encodeURIComponent(params[key])
        }).join('&')
        location.href = url + '/Home/Api/check?' + params
      })
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
