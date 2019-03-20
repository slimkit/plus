<template>
  <div class="c-reference-news">
    <h5 v-if="noContent">{{ $t('message.not_exist') }}</h5>
    <RouterLink v-else :to="`/news/${id}`">
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
  </div>
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
      noContent: false,
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
    async fetchNews () {
      const { data: news, status } = await api.getNewsById(this.id, { allow404: true })
      if (status === 404) {
        this.noContent = true
      } else {
        this.news = news
      }
    },
  },
}
</script>

<style lang="less" scoped>
.c-reference-news {
  padding: 15px 20px;
  background-color: #f4f5f5;
  font-size: 26px;

  > a {
    display: flex;
    align-items: center;
    color: #999;
  }

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
