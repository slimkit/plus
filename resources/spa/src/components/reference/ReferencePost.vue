<template>
  <div class="c-reference-post">
    <h5 v-if="noContent">{{ $t('message.not_exist') }}</h5>
    <RouterLink v-else :to="`/group/${post.group_id}/post/${id}`">
      <AsyncFile
        v-if="post.image"
        class="image"
        :file="post.image"
        :w="80"
        :h="80"
      >
        <div slot-scope="props" :style="{backgroundImage: `url(${props.src})`}" />
      </AsyncFile>
      <div>{{ post.title }}</div>
    </RouterLink>
  </div>
</template>

<script>
import * as api from '@/api/group'

export default {
  name: 'ReferencePost',
  props: {
    id: { type: Number, required: true },
  },
  data () {
    return {
      post: {},
      noContent: false,
    }
  },
  mounted () {
    this.fetchPost()
  },
  methods: {
    async fetchPost () {
      const { data: posts, status } = await api.getSimplePosts(`${this.id}`, { allow404: true })
      if (status === 404) {
        this.noContent = true
      } else {
        this.post = posts[0]
      }
    },
  },
}
</script>

<style lang="less" scoped>
.c-reference-post {
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
