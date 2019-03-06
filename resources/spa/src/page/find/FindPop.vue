<template>
  <JoLoadMore
    key="find-pop"
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
import { limit } from '@/api'
import * as api from '@/api/user.js'
import UserItem from '@/components/UserItem.vue'

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
    onRefresh (callback) {
      api.findUserByType('populars').then(({ data: users } = {}) => {
        users && (this.users = users)
        this.$refs.loadmore.afterRefresh(users.length < limit)
      })
    },
    onLoadMore (callback) {
      api.findUserByType('populars', {
        offset: this.users.length,
      }).then(({ data: users }) => {
        this.users = [...this.users, ...users]
        this.$refs.loadmore.afterLoadMore(users.length < limit)
      })
    },
  },
}
</script>
