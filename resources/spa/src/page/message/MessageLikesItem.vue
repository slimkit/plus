<template>
  <div class="c-message-likes-item">
    <Avatar :user="like.sender" />
    <main>
      <div class="sender-info">
        <section class="user-info">
          <RouterLink class="username" :to="`/users/${like.sender.id}`">
            {{ like.sender.name }}
          </RouterLink>
          <span> 赞了你的{{ nameMap[like.resource.type] }}</span>
          <p class="time"><slot /></p>
        </section>

        <svg class="m-icon-svg m-svg-def"><use xlink:href="#icon-like" /></svg>
      </div>
      <div>
        <Component
          :is="componentMap[type]"
          :id="like.resource.id"
        />
      </div>
    </main>
  </div>
</template>

<script>
import ReferenceFeed from '@/components/reference/ReferenceFeed.vue'
import ReferenceNews from '@/components/reference/ReferenceNews.vue'
import ReferencePost from '@/components/reference/ReferencePost.vue'

const componentMap = {
  'feeds': ReferenceFeed,
  'news': ReferenceNews,
  'group-posts': ReferencePost,
}

const nameMap = {
  'feeds': '动态',
  'news': '资讯',
  'group-posts': '帖子',
}

export default {
  name: 'MessageLikesItem',
  components: {
    ReferenceFeed,
    ReferenceNews,
    ReferencePost,
  },
  props: {
    like: { type: Object, required: true },
  },
  data () {
    return {
      componentMap,
      nameMap,
    }
  },
  computed: {
    type () {
      return this.like.resource.type
    },
  },
}
</script>

<style lang="less" scoped>
.c-message-likes-item {
  display: flex;

  .m-avatar-box {
    margin-right: 15px;
  }

  > main {
    flex: auto;
  }

  .sender-info {
    flex: auto;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;

    .user-info {
      font-size: 28px;
      color: #999;
    }

    .reply {
      flex: none;
      font-size: 26px;
      color: #999;
    }

    .time {
      font-size: 26px;
      color: #ccc;
    }
  }
}
</style>
