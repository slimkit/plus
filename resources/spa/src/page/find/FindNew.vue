<template>
  <jo-load-more
    key="find-new"
    ref="loadmore"
    @onRefresh="onRefresh"
    @onLoadMore="onLoadMore">
    <user-item
      v-for="user in users"
      :user="user"
      :key="user.id"/>
  </jo-load-more>
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
