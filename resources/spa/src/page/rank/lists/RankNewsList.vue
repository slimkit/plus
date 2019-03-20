<template>
  <div :class="prefixCls">
    <CommonHeader>{{ title | t('rank.news') }}</CommonHeader>

    <JoLoadMore
      ref="loadmore"
      @onRefresh="onRefresh"
      @onLoadMore="onLoadMore"
    >
      <div :class="`${prefixCls}-list`">
        <RankListItem
          v-for="(user, index) in users"
          :key="user.id"
          :prefix-cls="prefixCls"
          :user="user"
          :index="index"
        >
          <p>{{ user.extra.count || 0 | t('rank.news_count') }}</p>
        </RankListItem>
      </div>
    </JoLoadMore>
  </div>
</template>

<script>
import RankListItem from '../components/RankListItem.vue'
import { getRankUsers } from '@/api/ranks.js'
import { limit } from '@/api'
import i18n from '@/i18n'

const prefixCls = 'rankItem'
const api = '/news/ranks'
const config = {
  week: {
    vuex: 'rankNewsWeek',
    title: i18n.t('date.week'),
    query: 'week',
  },
  today: {
    vuex: 'rankNewsToday',
    title: i18n.t('date.today'),
    query: 'day',
  },
  month: {
    vuex: 'rankNewsMonth',
    title: i18n.t('date.month'),
    query: 'month',
  },
}

export default {
  name: 'NewsList',
  components: {
    RankListItem,
  },
  data () {
    return {
      prefixCls,
      loading: false,
      title: '', // 标题
      vuex: '', // vuex主键
      query: '', // api查询query
    }
  },

  computed: {
    users () {
      return this.$store.getters.getUsersByType(this.vuex)
    },
  },

  created () {
    let time = this.$route.params.time || 'today'
    this.title = config[time].title
    this.vuex = config[time].vuex
    this.query = config[time].query
    if (this.users.length === 0) {
      this.onRefresh()
    }
  },

  methods: {
    cancel () {
      this.to('/rank/news')
    },
    to (path) {
      path = typeof path === 'string' ? { path } : path
      if (path) {
        this.$router.push(path)
      }
    },
    onRefresh () {
      getRankUsers(api, { type: this.query }).then(data => {
        this.$store.commit('SAVE_RANK_DATA', { name: this.vuex, data })
        this.$refs.loadmore.afterRefresh(data.length <
 limit)
      })
    },
    onLoadMore () {
      getRankUsers(api, {
        type: this.query,
        offset: this.users.length || 0,
      }).then((data = []) => {
        this.$store.commit('SAVE_RANK_DATA', {
          name: this.vuex,
          data: [...this.users, ...data],
        })
        this.$refs.loadmore.afterLoadMore(data.length < limit)
      })
    },
  },
}
</script>

<style lang="less" src="../style.less">
</style>
