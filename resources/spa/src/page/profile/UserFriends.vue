<template>
  <div class="p-user-friends">
    <SearchBar v-model="keyword" />

    <JoLoadMore
      ref="loadmore"
      @onRefresh="onRefresh"
      @onLoadMore="onLoadMore"
    >
      <ul class="user-list">
        <FriendItem
          v-for="user in friends"
          :key="user.id"
          :user="user"
        />
      </ul>
    </JoLoadMore>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import SearchBar from '@/components/common/SearchBar.vue'
import FriendItem from './components/FriendItem.vue'

export default {
  name: 'UserFriends',
  components: {
    SearchBar,
    FriendItem,
  },
  data () {
    return {
      keyword: '',
    }
  },
  computed: {
    ...mapState('user', {
      friends: 'friends',
    }),
  },
  watch: {
    keyword () {
      this.$refs.loadmore.beforeRefresh()
    },
  },
  methods: {
    async onRefresh () {
      const params = {
        keyword: this.keyword,
      }
      const noMore = await this.$store.dispatch('user/getUserFriends', params)
      this.$refs.loadmore.afterRefresh(noMore)
    },
    async onLoadMore () {
      const params = {
        keyword: this.keyword,
        offset: this.friends.length,
      }
      const noMore = await this.$store.dispatch('user/getUserFriends', params)
      this.$refs.loadmore.afterRefresh(noMore)
    },
  },
}
</script>

<style lang="less" scoped>
.p-user-friends {
  .user-list {
    background-color: #fff;
  }
}
</style>
