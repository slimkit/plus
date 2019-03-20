<template>
  <div class="p-profile-collection-news">
    <JoLoadMore
      ref="loadmore"
      :auto-load="false"
      @onRefresh="onRefresh"
      @onLoadMore="onLoadMore"
    >
      <ul>
        <li
          v-for="news in newsList"
          :key="`collected-${news.id}`"
          class="news-item"
        >
          <NewsCard :news="news" />
        </li>
      </ul>
    </JoLoadMore>
  </div>
</template>

<script>
import * as api from '@/api/news'
import { limit } from '@/api'
import NewsCard from '@/page/news/components/NewsCard.vue'

export default {
  name: 'ProfileCollectionNews',
  components: { NewsCard },
  data () {
    return {
      newsList: [],
    }
  },
  mounted () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    onRefresh () {
      // TODO: refactor there with vuex action.
      api.getCollectedNews().then(({ data }) => {
        this.newsList = data
        this.$refs.loadmore.afterRefresh(data.length < limit)
      })
    },
    onLoadMore () {
      const after =
        this.newsList.length > 0
          ? this.newsList[this.newsList.length - 1].id
          : 0
      // TODO: refactor there with vuex action.
      api.getCollectedNews({ after }).then(({ data }) => {
        this.newsList = [...this.newsList, ...data]
        this.$refs.loadmore.afterLoadMore(data.length < limit)
      })
    },
  },
}
</script>
