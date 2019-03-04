<template>
  <RouterLink class="c-reference-post" :to="`/group/${post.group_id}/post/${id}`">
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
    }
  },
  mounted () {
    this.fetchPost()
  },
  methods: {
    fetchPost () {
      api.getSimplePosts(`${this.id}`)
        .then(({ data }) => {
          this.post = data[0]
        })
    },
  },
}
</script>

<style lang="less" scoped>
.c-reference-post {
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
