<template>
  <div class="p-user-fans">
    <nav class="m-box m-head-top m-lim-width m-pos-f m-main m-bb1" style="padding: 0 10px;">
      <div class="m-box m-aln-center m-flex-shrink0 ">
        <svg class="m-style-svg m-svg-def" @click="goBack">
          <use xlink:href="#icon-back" />
        </svg>
      </div>
      <ul class="m-box m-flex-grow1 m-aln-center m-justify-center m-flex-base0 m-head-nav">
        <RouterLink
          :to="`/users/${userId}/followers`"
          tag="li"
          active-class="active"
          exact
          replace
        >
          <a>粉丝</a>
        </RouterLink>
        <RouterLink
          :to="`/users/${userId}/followings`"
          tag="li"
          active-class="active"
          exact
          replace
        >
          <a>关注</a>
        </RouterLink>
      </ul>
      <div class="m-box m-justify-end" />
    </nav>

    <main style="padding-top: 0.9rem">
      <JoLoadMore
        ref="loadmore"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <UserItem
          v-for="user in users"
          v-if="user.id"
          :key="`user-item-${user.id}`"
          :user="user"
        />
      </JoLoadMore>
    </main>
  </div>
</template>

<script>
import UserItem from '@/components/UserItem'
import { getUserFansByType } from '@/api/user.js'

const typeMap = ['followers', 'followings']
export default {
  name: 'UserFans',
  components: {
    UserItem,
  },
  data () {
    return {
      followers: [],
      followings: [],
      preUID: 0,
      USERSChangeTracker: 1,
    }
  },
  computed: {
    userId () {
      return ~~this.$route.params.userId
    },
    type () {
      return this.$route.params.type
    },
    param () {
      return {
        limit: 15,
        type: this.type,
        uid: this.userId,
      }
    },
    users: {
      get () {
        return this.type && this.$data[this.type]
      },
      set (val) {
        this.$data[this.type] = val
      },
    },
  },
  watch: {
    type (val) {
      typeMap.includes(val) && this.$refs.loadmore.beforeRefresh()
    },
    users (val) {
      if (val && val.length > 0) {
        this.$store.commit('SAVE_USER', val)
      }
    },
  },
  activated () {
    // 判断是否清空上一次的数据
    if (this.userId !== this.preUID) {
      this.followers = []
      this.followings = []
    }

    this.$refs.loadmore.beforeRefresh()
    this.preUID = this.userId
  },
  methods: {
    onRefresh () {
      getUserFansByType(this.param).then(data => {
        this.users = data
        this.$refs.loadmore.afterRefresh(data.length < this.param.limit)
      })
    },
    onLoadMore (callback) {
      getUserFansByType({ ...this.param, offset: this.users.length }).then(
        data => {
          this.users = [...this.users, ...data]
          this.$refs.loadmore.afterLoadMore(data.length < this.param.limit)
        }
      )
    },
  },
}
</script>
