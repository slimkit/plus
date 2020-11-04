<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <Avatar :user="user" />
      <section class="userInfo">
        <!-- eslint-disable-next-line vue/component-name-in-template-casing -->
        <i18n path="message.like.liked" :slot-scope="{type: $t('article.type.news')}">
          <span
            slot="user"
            :class="`${prefixCls}-item-top-link`"
            @click="viewUser(user.id)"
          >
            {{ user.name }}
          </span>
        </i18n>
        <p>{{ like.created_at | time2tips }}</p>
      </section>
      <svg class="m-style-svg m-svg-def m-flex-grow0 m-shrink0">
        <use xlink:href="#icon-like" />
      </svg>
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
          {{ $t('article.deleted') }}
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
