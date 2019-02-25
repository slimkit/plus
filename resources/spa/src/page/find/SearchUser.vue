<template>
  <Transition
    enter-active-class="animated slideInRight"
    leave-active-class="animated slideOutLeft"
  >
    <div class="m-box-model m-pos-f p-search-user">
      <SearchBar v-model="keyword" />

      <main class="m-flex-grow1 m-flex-shrink1 p-search-user-body">
        <JoLoadMore
          v-show="showRec"
          ref="loadmoreRecs"
          :show-bottom="false"
          :no-animation="true"
          @onRefresh="fetchRecs"
        >
          <UserItem
            v-for="user in recs"
            :key="user.id"
            :user="user"
          />
        </JoLoadMore>
        <JoLoadMore
          v-show="users.length > 0"
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
        <div v-if="noData" class="placeholder m-no-find" />
      </main>
    </div>
  </Transition>
</template>

<script>
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
      show: true,
      users: [],
      recs: [],
      isFocus: false,
      noData: false,
      keyword: '',
    }
  },
  computed: {
    showRec () {
      return this.keyword.length === 0 && !this.isFocus
    },
  },
  watch: {
    keyword () {
      this.searchUserByKey()
    },
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
