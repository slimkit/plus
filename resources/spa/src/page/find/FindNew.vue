<template>
  <JoLoadMore
    key="find-new"
    ref="loadmore"
    @onRefresh="onRefresh"
    @onLoadMore="onLoadMore"
  >
    <UserItem
      v-for="user in users"
      :key="user.id"
      :user="user"
    />
  </JoLoadMore>
</template>
<script>
import UserItem from '@/components/UserItem.vue'
import { findUserByType } from '@/api/user.js'
export default {
  name: 'FindPop',
  components: {
    UserItem,
  },
  data () {
    return {
      users: [],
    }
  },
  activated () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    onRefresh () {
      findUserByType('latests').then(({ data: users } = {}) => {
        users && (this.users = users)
        this.$refs.loadmore.afterRefresh(users.length < 15)
      })
    },
    onLoadMore () {
      findUserByType('latests', {
        offset: this.users.length,
      }).then(({ data: users }) => {
        this.users = [...this.users, ...users]
        this.$refs.loadmore.afterLoadmore(users.length < 15)
      })
    },
  },
}
</script>
