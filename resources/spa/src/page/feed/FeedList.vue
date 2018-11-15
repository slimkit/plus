<template>
  <div class="p-feed">
    <nav class="m-box m-head-top m-lim-width m-pos-f m-main m-bb1">
      <ul class="m-box m-flex-grow1 m-aln-center m-justify-center m-flex-base0 m-head-nav">
        <router-link
          :to="{ name:'feeds', query: { type: 'new' } }"
          tag="li"
          active-class="active"
          exact
          replace>
          <a>最新</a>
        </router-link>
        <router-link
          :to="{ name:'feeds', query: { type: 'hot' } }"
          tag="li"
          active-class="active"
          exact
          replace>
          <a>热门</a>
        </router-link>
        <router-link
          :to="{ name:'feeds', query: { type: 'follow' } }"
          tag="li"
          active-class="active"
          exact
          replace>
          <a>关注</a>
        </router-link>
      </ul>
    </nav>

    <jo-load-more
      ref="loadmore"
      :auto-load="true"
      class="p-feed-main"
      @onRefresh="onRefresh"
      @onLoadMore="onLoadMore" >

      <ul class="p-feed-list">
        <li
          v-for="(feed, index) in pinned"
          v-if="feed.id"
          :key="`pinned-feed-${feedType}-${feed.id}-${index}`">
          <feed-card
            :feed="feed"
            :pinned="true" />
        </li>
        <li
          v-for="(card, index) in feeds"
          :key="`feed-${feedType}-${card.id}-${index}`">
          <feed-card
            v-if="card.user_id"
            :feed="card" />
          <feed-ad-card
            v-if="card.space_id"
            :ad="card"/>
        </li>
      </ul>
    </jo-load-more>
    <foot-guide/>
  </div>
</template>

<script>
/**
 * 动态列表
 * @typedef {{id: number, user, ...others}} FeedDetail
 */

import FeedCard from "@/components/FeedCard/FeedCard.vue";
import { noop } from "@/util";

const feedTypesMap = ["new", "hot", "follow"];

export default {
  name: "FeedList",
  components: { FeedCard },
  data() {
    return {};
  },
  computed: {
    feedType() {
      return this.$route.query.type;
    },
    feeds() {
      return this.$store.getters[`feed/${this.feedType}`];
    },
    pinned() {
      return this.$store.getters["feed/pinned"];
    },
    after() {
      const len = this.feeds.length;
      if (!len) return 0;
      if (this.feedType !== "hot") return this.feeds[len - 1].id; // after
      return this.feeds[len - 1].hot; // offset
    }
  },
  watch: {
    feedType(val, oldVal) {
      feedTypesMap.includes(val) &&
        oldVal &&
        this.$refs.loadmore.beforeRefresh();
    }
  },
  created() {
    this.onRefresh(noop);
  },
  activated() {
    if (this.$route.query.refresh) {
      this.onRefresh();
    }
  },
  methods: {
    async onRefresh() {
      const type = this.feedType.replace(/^\S/, s => s.toUpperCase());
      const action = `feed/get${type}Feeds`;
      const data = await this.$store.dispatch(action, { refresh: true });
      this.$refs.loadmore.afterRefresh(data.length < 15);
    },
    async onLoadMore() {
      const type = this.feedType.replace(/^\S/, s => s.toUpperCase());
      const action = `feed/get${type}Feeds`;
      const data = await this.$store.dispatch(action, { after: this.after });
      this.$refs.loadmore.afterLoadMore(data.length < 15);
    }
  }
};
</script>

<style lang="less" scoped>
.p-feed {
  .p-feed-main {
    padding-top: 90px;
  }

  .p-feed-list > li + li {
    margin-top: 20px;
  }
}
</style>
