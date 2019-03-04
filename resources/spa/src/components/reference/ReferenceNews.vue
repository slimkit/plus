<template>
  <RouterLink class="c-reference-news" :to="`/news/${id}`">
    <AsyncFile
      v-if="image"
      class="image"
      :file="image.file"
      :w="80"
      :h="80"
    >
      <div slot-scope="props" :style="{backgroundImage: `url(${props.src})`}" />
    </AsyncFile>
    <div>{{ news.title }}</div>
  </RouterLink>
</template>

<script>
import * as api from '@/api/news'

export default {
  name: 'ReferenceNews',
  props: {
    id: { type: Number, required: true },
  },
  data () {
    return {
      news: {},
    }
  },
  computed: {
    image () {
      return this.news.image
    },
  },
  mounted () {
    this.fetchNews()
  },
  methods: {
    fetchNews () {
      api.getNewsById(this.id)
        .then(({ data: news }) => {
          this.news = news
        })
    },
  },
}
</script>

<style lang="less" scoped>
.c-reference-news {
  display: flex;
  align-items: center;
  padding: 15px 20px;
  background-color: #f4f5f5;
  color: #999;
  font-size: 26px;

  .image {
    width: 80px;
    height: 80px;
    margin-right: 15px;

    > div {
      width: 100%;
      height: 100%;
      background: no-repeat center / cover;
    }
  }

}
</style>
