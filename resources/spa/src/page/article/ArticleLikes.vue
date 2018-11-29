<template>
  <div class="m-art-likes">
    <CommonHeader>点赞列表</CommonHeader>

    <main>
      <JoLoadMore
        ref="loadmore"
        :auto-load="false"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore"
      >
        <UserItem
          v-for="({ user, id }, index) in likes"
          :key="`likes-${id}-${user.id}-${index}`"
          :user="user"
        />
      </JoLoadMore>
    </main>
  </div>
</template>

<script>
import UserItem from '@/components/UserItem.vue'

export default {
  name: 'ArticleLikes',
  components: {
    UserItem,
  },
  data () {
    return {
      likes: [],
      maxId: 0,
    }
  },
  computed: {
    type () {
      return this.$route.meta.type
    },
    article () {
      return this.$route.params.article
    },
    url () {
      // 动态  GET /feeds/:feed/likes
      // 资讯  GET /news/:news/likes
      // 帖子  GET /plus-group/group-posts/:post/likes
      let result
      switch (this.type) {
        case 'feed':
          result = `/feeds/${this.article}/likes`
          break
        case 'news':
          result = `/news/${this.article}/likes`
          break
        case 'post':
          result = `/plus-group/group-posts/${this.article}/likes`
          break
        case 'answer':
          result = `/question-answers/${this.article}/likes`
          break
      }
      return result
    },
  },
  mounted () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    goBack () {
      window.history.length <= 1
        ? this.$router.push(`/feeds/${this.article}`)
        : this.$router.go(-1)
    },
    onRefresh (callback) {
      //  名称    类型       描述
      //  limit   Integer   获取条数，默认 20
      //  after   Integer   id 获取之后数据，默认 0
      this.$http
        .get(this.url, { params: { limit: 15 } })
        .then(({ data = [] }) => {
          this.likes = data
          data.length > 0 && (this.maxId = data[data.length - 1].id)
          this.$refs.loadmore.afterRefresh(data.length < 15)
        })
    },
    onLoadMore (callback) {
      this.$http
        .get(this.url, {
          params: {
            limit: 15,
            after: this.maxId,
          },
        })
        .then(({ data = [] }) => {
          this.likes = [...this.likes, ...data]
          data.length > 0 && (this.maxId = data[data.length - 1].id)
          this.$refs.loadmore.afterLoadMore(data.length < 15)
        })
    },
  },
}
</script>
