<template>
  <div class="m-box-model m-pos-f p-search-user">
    <SearchBar v-model="keyword" />

    <main class="m-flex-grow1 m-flex-shrink1 p-search-user-body">
      <JoLoadMore
        ref="loadmore"
        :show-bottom="!!keyword"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <template v-if="keyword">
          <UserItem
            v-for="user in users"
            :key="user.id"
            :user="user"
          />
        </template>
        <template v-else>
          <p class="recommend">{{ $t('find.user.recommend') }}</p>
          <UserItem
            v-for="user in recommendUsers"
            :key="user.id"
            :user="user"
          />
        </template>
      </JoLoadMore>
      <div v-if="noData" class="placeholder m-no-find" />
    </main>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import _ from 'lodash'
import { limit } from '@/api'
import * as api from '@/api/user.js'
import SearchBar from '@/components/common/SearchBar.vue'
import UserItem from '@/components/UserItem.vue'

export default {
  name: 'SearchUser',
  components: {
    UserItem,
    SearchBar,
  },
  data () {
    return {
      keyword: '',
      users: [],
      noData: false,
    }
  },
  computed: {
    ...mapState('user', {
      recommendUsers: 'recommend',
    }),
  },
  watch: {
    keyword () {
      this.$refs.loadmore.beforeRefresh()
    },
  },
  mounted () {
    this.$store.dispatch('user/getRecommendUsers')
  },
  deactivated () {
    this.keyword = ''
  },
  methods: {
    goBack () {
      this.keyword = ''
      this.isFocus = false
      this.$router.go(-1)
    },
    /**
     * 使用 lodash.debounce 防抖，每输入 600ms 后执行
     * 不要使用箭头函数，会导致 this 作用域丢失
     * @author mutoe <mutoe@foxmail.com>
     */
    searchUserByKey: _.debounce(function () {
      api.searchUserByKey(this.keyword).then(({ data }) => {
        this.users = data
        this.noData = data.length === 0 && this.keyword.length > 0
      })
    }, 600),
    onRefresh (callback) {
      api.searchUserByKey(this.keyword).then(({ data }) => {
        this.users = data
        this.$refs.loadmore.afterRefresh(data.length < limit)
      })
    },
    onLoadMore (callback) {
      api.searchUserByKey(this.keyword, this.users.length).then(({ data }) => {
        this.users = [...this.users, ...data]
        this.$refs.loadmore.afterLoadMore(data.length < limit)
      })
    },
    onFocus () {
      this.isFocus = true
      this.noData = false
    },
    onBlur () {
      this.isFocus = false
    },
    fetchRecs (callback) {
      api.findUserByType('recommends').then(({ data }) => {
        this.recs = data
        this.$refs.loadmoreRecs.afterRefresh(data.length < limit)
      })
    },
  },
}
</script>

<style lang="less">
.p-search-user {
  z-index: 100;
  background-color: #f4f5f6;
  animation-duration: 0.3s;

  header {
    padding: 20px 30px;
    bottom: initial;
  }

  .recommend {
    padding: 15px 15px 0;
    background-color: #fff;
    color: #999;
    font-size: 28px;
  }

  .m-search-box {
    margin-right: 30px;
  }

  .p-search-user-body {
    overflow-y: auto;
  }

  .m-no-find {
    width: 100vw;
    height: 100vh;
    position: fixed;
  }
}
</style>
