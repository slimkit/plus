<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <Avatar :user="user" />
      <section class="userInfo">
        <span v-if="!user.id" :class="`${prefixCls}-item-top-link`">
          未知用户
        </span>
        <RouterLink :class="`${prefixCls}-item-top-link`" :to="`/users/${user._id}`">
          {{ user.name }}
        </RouterLink>
        <span>赞了你的资讯</span>
        <p>{{ like.created_at | time2tips }}</p>
      </section>
    </div>
    <div :class="`${prefixCls}-item-bottom`">
      <!-- <section v-if="like.likeable !== null" @click="goToFeedDetail()"> -->
      <section v-if="like.likeable !== null">
        <div
          v-if="!getImage"
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content"
        >
          {{ like.likeable.title }}
        </div>
        <div v-else :class="`${prefixCls}-item-bottom-img`">
          <div class="img">
            <img :src="getImage" :alt="user.name">
          </div>
          <div class="content">
            {{ like.likeable.title }}
          </div>
        </div>
      </section>
      <section v-if="like.likeable === null">
        <div :class="`${prefixCls}-item-bottom-noImg`" class="content">
          资讯已被删除
        </div>
      </section>
    </div>
  </section>
</template>

<script>
import MessageLikeItemBase from './MessageLikeItemBase'

export default {
  name: 'MessageLikeNewsItem',
  extends: MessageLikeItemBase,
  methods: {
    goToFeedDetail () {
      const { likeable: { id = 0 } } = this.like
      this.$router.push(`/news/${id}`)
    },
  },
}
</script>
