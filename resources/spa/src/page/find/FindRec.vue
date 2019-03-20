<template>
  <JoLoadMore
    key="find-rec"
    ref="loadmore"
    @onRefresh="onRefresh"
    @onLoadMore="onLoadMore"
  >
    <UserItem
      v-for="user in users"
      :key="`${user.id}-from-${user.searchFrom}`"
      :user="user"
    />
  </JoLoadMore>
</template>

<script>
import { limit } from '@/api'
import * as userApi from '@/api/user'
import UserItem from '@/components/UserItem.vue'

export default {
  name: 'FindRec',
  components: { UserItem },
  data () {
    return {
      users: this.$store.state.user.recommend || [],
    }
  },
  activated () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    onRefresh () {
      const recommendPromise = userApi
        .findUserByType('recommends')
        .then(({ data: users }) => {
          this.users = users
          return users.map(u => {
            u.searchFrom = 'recommend'
            return u
          })
        })
      const tagsPromise = userApi
        .findUserByType('find-by-tags')
        .then(({ data: users }) => {
          this.users = users
          this.$refs.loadmore.afterRefresh(users.length < limit)
          return users.map(u => {
            u.searchFrom = 'tags'
            return u
          })
        })
      // 并发获取用户
      Promise.all([recommendPromise, tagsPromise])
        .then(([recommendUsers, tagsUsers]) => {
          this.users = [...recommendUsers, ...tagsUsers]
        })
        .catch(err => {
          this.$refs.loadmore.afterRefresh(false)
          return err
        })
    },
    async onLoadMore () {
      const { data: users } = await userApi.findUserByType('find-by-tags', {
        offset: this.users.length,
      })
      this.users = [...this.users, ...users]
      this.$refs.loadmore.afterLoadMore(users.length < limit)
    },
  },
}
</script>
