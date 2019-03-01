<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <Avatar :user="user" />
      <section class="userInfo">
        <span
          v-if="!user.id"
          :class="`${prefixCls}-item-top-link`"
        >
          未知用户
        </span>
        <RouterLink
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${user._id}`"
        >
          {{ user.name }}
        </RouterLink>
        <span>赞了你的帖子</span>
        <p>{{ like.created_at | time2tips }}</p>
      </section>
    </div>
    <div :class="`${prefixCls}-item-bottom`">
      <section
        v-if="like.likeable !== null"
        @click="goToFeedDetail()"
      >
        <div
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content"
        >
          {{ like.likeable.title }}
        </div>
        <!-- <div :class="`${prefixCls}-item-bottom-img`" v-else>
          <div class="img">
            <img :src="getImage" :alt="user.name">
          </div>
          <div class="content">
            {{ like.likeable.title }}
          </div>
        </div> -->
      </section>
      <section v-if="like.likeable === null">
        <div
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content"
        >
          帖子已被删除
        </div>
      </section>
    </div>
  </section>
</template>

<script>
import MessageLikeItemBase from './MessageLikeItemBase'

export default {
  name: 'MessageLikePostItem',
  extends: MessageLikeItemBase,
  methods: {
    goToFeedDetail () {
      const { likeable: { id = 0 } } = this.like
      this.$router.push(`/feeds/${id}`)
    },
  },
}
</script>
