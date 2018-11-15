<template>
  <div class="p-news-search">

    <search-bar v-model="keywordOrigin" />

    <jo-load-more
      ref="loadmore"
      :auto-load="false"
      :show-bottom="list.length > 0"
      @onLoadMore="onLoadMore">
      <news-card
        v-for="news in list"
        v-if="news.id"
        :key="news.id"
        :news="news" />
    </jo-load-more>
    <p v-show="loading" class="load-more-ph m-text-c mt10">正在搜索...</p>
    <div v-show="noResult && !loading && keyword && !list.length" class="placeholder m-no-find"/>
  </div>
</template>

<script>
import _ from "lodash";
import SearchBar from "@/components/common/SearchBar.vue";
import NewsCard from "./components/NewsCard.vue";
import { searchNewsByKey } from "@/api/news.js";
import { limit } from "@/api";

export default {
  name: "NewsSearch",
  components: {
    NewsCard,
    SearchBar
  },
  data() {
    return {
      keywordOrigin: "",
      list: [],
      loading: false,
      noResult: false
    };
  },
  computed: {
    after() {
      const len = this.list.length;
      return len > 0 ? this.list[len - 1].id : 0;
    },
    keyword() {
      return this.keywordOrigin.trim();
    }
  },
  watch: {
    keyword() {
      this.searchNewsByKey();
    }
  },
  methods: {
    /**
     * 使用 lodash.debounce 防抖，每输入 600ms 后执行
     * 不要使用箭头函数，会导致 this 作用域丢失
     * @author mutoe <mutoe@foxmail.com>
     */
    searchNewsByKey: _.debounce(function() {
      if (!this.keyword) return;
      this.loading = true;
      searchNewsByKey(this.keyword).then(({ data: list }) => {
        this.loading = false;
        this.list = list;
        this.$refs.loadmore.afterRefresh(list.length < limit);
        if (!list.length) this.noResult = true;
      });
    }, 600),
    onLoadMore() {
      searchNewsByKey(this.keyword, limit, this.after).then(
        ({ data: list }) => {
          this.list = [...this.list, ...list];
          this.$refs.loadmore.afterLoadmore(list.length < limit);
        }
      );
    }
  }
};
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
