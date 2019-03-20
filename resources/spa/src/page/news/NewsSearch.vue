<template>
  <div class="p-news-search">
    <SearchBar v-model="keyword" />

    <JoLoadMore
      ref="loadmore"
      :auto-load="false"
      :show-bottom="list.length > 0"
      @onRefresh="onRefresh"
      @onLoadMore="onLoadMore"
    >
      <NewsCard
        v-for="news in list"
        v-if="news.id"
        :key="news.id"
        :news="news"
      />
    </JoLoadMore>
    <div
      v-show="noResult && !loading && keyword && !list.length"
      class="placeholder m-no-find"
    />
  </div>
</template>

<script>
import SearchBar from '@/components/common/SearchBar.vue'
import NewsCard from './components/NewsCard.vue'
import { searchNewsByKey } from '@/api/news.js'
import { limit } from '@/api'

export default {
  name: 'NewsSearch',
  components: {
    NewsCard,
    SearchBar,
  },
  data () {
    return {
      keyword: '',
      list: [],
      loading: false,
      noResult: false,
    }
  },
  computed: {
    after () {
      const len = this.list.length
      return len > 0 ? this.list[len - 1].id : 0
    },
  },
  watch: {
    keyword () {
      this.$refs.loadmore.beforeRefresh()
    },
  },
  methods: {
    onRefresh () {
      if (!this.keyword) return (this.list = [])
      this.loading = true
      searchNewsByKey(this.keyword).then(({ data: list }) => {
        this.loading = false
        this.list = list
        this.$refs.loadmore.afterRefresh(list.length < limit)
        if (!list.length) this.noResult = true
      })
    },
    onLoadMore () {
      searchNewsByKey(this.keyword, limit, this.after).then(
        ({ data: list }) => {
          this.list = [...this.list, ...list]
          this.$refs.loadmore.afterLoadmore(list.length < limit)
        }
      )
    },
  },
}
</script>

<style lang="less" scoped>
.p-news-search {
  height: ~"calc(100% - 90px)";

  .m-head-top-title {
    padding: 0 20px 0 0;

    .m-search-box {
      width: 100%;
    }
  }

  .placeholder {
    width: 100%;
    height: 100%;
  }
}
</style>

<style lang="less">
.jo-loadmore-head {
  top: 0px;
}
</style>
