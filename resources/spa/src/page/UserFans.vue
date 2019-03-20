<template>
  <div class="p-user-fans">
    <CommonHeader :pinned="true" class="header">
      <nav class="type-switch-bar">
        <span :class="{active: type === 'followers'}" @click="type = 'followers'">
          {{ $t('fans') }}
        </span>
        <span :class="{active: type === 'followings'}" @click="type = 'followings'">
          {{ $t('follow.name') }}
        </span>
      </nav>
    </CommonHeader>

    <main>
      <JoLoadMore
        ref="loadmore"
        :auto-load="false"
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
import { limit } from '@/api'
import { getUserFansByType } from '@/api/user.js'
import UserItem from '@/components/UserItem'

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
      return Number(this.$route.params.userId)
    },
    type: {
      get () {
        const { path } = this.$route
        return path.match(/\/(\w+)$/)[1]
      },
      set (val) {
        this.$router.replace({ path: `/users/${this.userId}/${val}` })
      },
    },
    param () {
      return {
        limit,
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
