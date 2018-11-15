<template>
  <router-link
    :to="`/news/${news.id}`"
    tag="div"
    class="news-card">
    <section class="body">
      <h2>{{ title }}</h2>
      <p>
        <i v-show="!currentCate" class="news-cate">{{ cate }}</i>
        <span>{{ author }}</span>
        <span>・{{ hits }}浏览</span>
        <span>・{{ time | time2tips }}</span>
      </p>
    </section>
    <div v-if="image" class="poster">
      <img :src="image">
    </div>
  </router-link>
</template>

<script>
export default {
  name: "NewsCard",
  props: {
    currentCate: { type: Number, default: 0 },
    news: { type: Object, required: true }
  },
  data() {
    return {
      hits: 0,
      cate: null,
      image: null,
      title: null,
      author: null,
      time: ""
    };
  },
  mounted() {
    this.formatData();
  },
  methods: {
    formatData() {
      const {
        hits = 0,
        author = "",
        image = {},
        title = "",
        created_at: createdAt,
        category = {}
      } = this.news;
      this.hits = hits;
      this.title = title;
      this.author = author;
      this.time = createdAt;
      this.cate = category.name;
      this.image = image
        ? `${this.$http.defaults.baseURL}/files/${image.id}?w=190&h=135`
        : null;
    }
  }
};
</script>

<style lang="less" scoped>
.news-card {
  display: flex;
  flex-wrap: nowrap;
  align-items: center;
  padding: 30px 20px;
  height: 195px;
  background-color: #fff;
  border-bottom: 1px solid #ededed; /*no*/

  .body {
    display: flex;
    flex: auto;
    min-width: 0; // 不要删除此行，与 flex 弹性宽度有关
    height: 100%;
    flex-direction: column;
    justify-content: space-between;

    h2 {
      flex: 0 0 auto;
      margin: 0;
      max-height: 42 * 2px;
      font-size: 32px;
      color: #333;
      line-height: 42px;
      font-weight: normal;

      text-overflow: -o-ellipsis-lastline;
      overflow: hidden;
      text-overflow: ellipsis;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
    }

    p {
      font-size: 24px;
      color: #ccc;
      margin: 0;
      text-overflow: -o-ellipsis-lastline;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      vertical-align: middle;

      i.news-cate {
        padding: 4px;
        font-style: normal;
        display: inline-block;
        font-size: 20px;
        height: 30/0.95px;
        color: @primary;
        line-height: 30/0.95-10px;
        border: 1px solid currentColor; /*no*/
        -webkit-transform-origin-x: 0;
        -webkit-transform: scale(0.95);
        transform: scale(0.95);
      }
    }
  }

  .poster {
    margin-left: 40px;
    order: 1;
    overflow-y: hidden;
    flex: 0 0 190px;
    width: 190px;
    height: 135px;
    position: relative;

    img {
      position: absolute;
      top: 50%;
      left: 50%;
      width: 100%;
      transform: translate3d(-50%, -50%, 0);
    }
  }
}
</style>
