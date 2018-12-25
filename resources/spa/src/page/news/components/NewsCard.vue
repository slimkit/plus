<template>
  <RouterLink
    :to="`/news/${news.id}`"
    tag="div"
    class="news-card"
    :class="{ 'multi-image':images.length }"
  >
    <template v-if="images.length">
      <!-- 三张图 -->
      <h2 class="title">{{ title }}</h2>
      <ul class="images">
        <li v-for="img in images" :key="img.id">
          <div
            class="img"
            :style="{'background-image': `url(${getImageUri(img.id, 'w=190&h=135')})`}"
          />
        </li>
      </ul>
      <p class="info">
        <i v-show="!currentCate" class="news-cate"> {{ cate }} </i>
        <span>{{ author }}</span>
        <span>・{{ hits | t('article.views_count') }}</span>
        <span>・{{ time | time2tips }}</span>
      </p>
    </template>
    <template v-else>
      <section class="body">
        <h2 class="title">{{ title }}</h2>
        <p class="info">
          <i v-show="!currentCate" class="news-cate"> {{ cate }} </i>
          <span>{{ author }}</span>
          <span>・{{ hits | t('article.views_count') }}</span>
          <span>・{{ time | time2tips }}</span>
        </p>
      </section>
      <div v-if="image" class="poster">
        <img :src="image">
      </div>
    </template>
  </RouterLink>
</template>

<script>
export default {
  name: 'NewsCard',
  props: {
    currentCate: { type: Number, default: 0 },
    news: { type: Object, required: true },
  },
  data () {
    return {
      hits: 0,
      cate: null,
      image: null,
      title: null,
      author: null,
      time: '',
    }
  },
  computed: {
    images () {
      if (!this.news.images) return []
      if (this.news.images.length < 3) return []
      return this.news.images.slice(0, 3)
    },
  },
  mounted () {
    this.formatData()
  },
  methods: {
    formatData () {
      const {
        hits = 0,
        author = '',
        image = {},
        title = '',
        created_at: createdAt,
        category = {},
      } = this.news
      this.hits = hits
      this.title = title
      this.author = author
      this.time = createdAt
      this.cate = category.name
      this.image = image
        ? this.getImageUri(image.id, 'w=190&h=135')
        : null
    },
    getImageUri (id, query) {
      let uri = `${this.$http.defaults.baseURL}/files/${id}`
      if (query) uri += `?${query}`
      return uri
    },
  },
}
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

  &.multi-image {
    flex-direction: column;
    align-items: flex-start;
    height: 315px;
  }

  .body {
    display: flex;
    flex: auto;
    min-width: 0; // 不要删除此行，与 flex 弹性宽度有关
    height: 100%;
    flex-direction: column;
    justify-content: space-between;
  }

  .title {
    flex: none;
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

  .images {
    flex: auto;
    display: flex;
    justify-content: space-between;
    height: 135px;
    width: 100%;
    margin: 20px 0;
    overflow: hidden;

    li {
      flex: none;
      width: 32.5%;

      .img {
        background: no-repeat center;
        background-size: cover;
        width: 100%;
        height: 100%;
      }
    }
  }

  .info {
    flex: none;
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
      margin-right: 8px;
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
