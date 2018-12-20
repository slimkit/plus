<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <Avatar :user="user" />
      <section class="userInfo">
        <span v-if="!user.id" :class="`${prefixCls}-item-top-link`">
          未知用户
        </span>
        <RouterLink
          v-else
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${user._id}`"
        >
          {{ user.name || "未知用户" }}
        </RouterLink>
        <span>赞了你的回答</span>
        <p>{{ like.created_at | time2tips }}</p>
      </section>
    </div>
    <div :class="`${prefixCls}-item-bottom`">
      <section v-if="like.likeable !== null" @click="goToFeedDetail()">
        <div :class="`${prefixCls}-item-bottom-noImg`" class="content">
          {{ like.likeable.body }}
        </div>
        <!-- <div :class="`${prefixCls}-item-bottom-img`" v-else>
          <div class="img">
            <img :src="getImage" :alt="user.name">
          </div>
          <div class="content">
            {{ like.likeable.feed_content }}
          </div>
        </div> -->
      </section>
      <section v-if="like.likeable === null">
        <div :class="`${prefixCls}-item-bottom-noImg`" class="content">
          回答已被删除
        </div>
      </section>
    </div>
  </section>
</template>

<script>
import MessageLikeItemBase from './MessageLikeItemBase'

export default {
  name: 'MessageLikeAnswerItem',
  extends: MessageLikeItemBase,
  methods: {
    goToFeedDetail () {
      const { likeable: { id } } = this.like
      this.$router.push(`/questions/${id}`)
    },
  },
}
</script>
