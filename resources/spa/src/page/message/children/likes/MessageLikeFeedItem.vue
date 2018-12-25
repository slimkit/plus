<template>
  <section>
    <div :class="`${prefixCls}-item-top`" class="m-box m-aln-center m-justify-bet">
      <Avatar :user="user" />
      <section class="userInfo m-flex-grow1 m-flex-shrink1 m-flex-base0">
        <!-- eslint-disable-next-line vue/component-name-in-template-casing -->
        <i18n path="message.like.liked" :places="{type: $t('article.type.feed')}">
          <span
            place="user"
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
      <section v-if="like.likeable !== null" @click="goToFeedDetail()">
        <div
          v-if="!getImage && !getVideo"
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content"
        >
          {{ like.likeable.feed_content }}
        </div>
        <div v-else :class="`${prefixCls}-item-bottom-img`">
          <div class="img">
            <img
              v-if="getImage"
              :src="getImage"
              :alt="user.name"
            >
            <img
              v-if="getVideo"
              :src="getVideo.cover"
              :alt="user.name"
            >
          </div>
          <div class="content">
            {{ like.likeable.feed_content }}
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
  name: 'MessageLikeFeedItem',
  extends: MessageLikeItemBase,
  computed: {
    getVideo () {
      const { like } = this.$props
      const video = like.likeable.video
      if (video !== null) {
        return {
          video: `${this.$http.defaults.baseURL}/files/${video.video_id}`,
          cover: `${this.$http.defaults.baseURL}/files/${video.cover_id}`,
        }
      }
      return false
    },
  },
  methods: {
    goToFeedDetail () {
      const { likeable: { id = 0 } } = this.like
      this.$router.push(`/feeds/${id}`)
    },
  },
}
</script>
