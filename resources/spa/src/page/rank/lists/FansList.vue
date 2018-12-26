<template>
  <div :class="prefixCls">
    <CommonHeader>全站粉丝排行榜</CommonHeader>

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
          <p>粉丝：{{ user.extra.followers_count || 0 }}</p>
        </RankListItem>
      </div>
    </JoLoadMore>
  </div>
</template>

<script>
import RankListItem from '../components/RankListItem.vue'
import { getRankUsers } from '@/api/ranks.js'
import { limit } from '@/api'

const api = '/ranks/followers'
const prefixCls = 'rankItem'

export default {
  name: 'FansList',
  components: {
    RankListItem,
  },
  data () {
    return {
      prefixCls,
      loading: false,
      vuex: 'rankFollowers', // vuex主键
    }
  },

  computed: {
    users () {
      return this.$store.getters.getUsersByType(this.vuex)
    },
  },

  methods: {
    cancel () {
      this.to('/rank/users')
    },
    to (path) {
      path = typeof path === 'string' ? { path } : path
      if (path) {
        this.$router.push(path)
      }
    },
    onRefresh () {
      getRankUsers(api).then(data => {
        this.$store.commit('SAVE_RANK_DATA', { name: this.vuex, data })
        this.$refs.loadmore.afterRefresh(data.length <
 limit)
      })
    },
    onLoadMore () {
      getRankUsers(api, { offset: this.users.length || 0 }).then(
        (data = []) => {
          this.$store.commit('SAVE_RANK_DATA', {
            name: this.vuex,
            data: [...this.users, ...data],
          })
          this.$refs.loadmore.afterLoadMore(data.length < limit)
        }
      )
    },
  },
}
</script>

<style lang="less" src="../style.less">
</style>
