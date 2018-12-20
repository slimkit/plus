<template>
  <div>
    <div :class="`${prefixCls}-item-top`">
      <Avatar :user="user" />
      <section class="userInfo">
        <RouterLink :class="`${prefixCls}-item-top-link`" :to="`/users/${comment.user_id}`">
          {{ comment.user.name }}
        </RouterLink>
        <span v-if="comment.reply_user"> 回复</span>
        <span v-else> 评论了你的动态</span>
        <RouterLink
          v-if="comment.reply_user"
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${comment.reply_user}`"
        >
          {{ comment.reply.name }}
        </RouterLink>
        <p>{{ comment.created_at | time2tips }}</p>
      </section>
      <section class="msgList-status">
        <section class="gray">
          <span class="reply" @click.stop="showCommentInput">
            回复
          </span>
        </section>
      </section>
    </div>
    <div :class="`${prefixCls}-item-bottom`">
      <span class="content" @click.stop="showCommentInput">
        {{ comment.body }}
      </span>
      <section v-if="comment.commentable !== null" @click="goToDetail()">
        <div
          v-if="!getFirstImage && !getVideo"
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content"
        >
          {{ comment.commentable.feed_content }}
        </div>
        <div v-else :class="`${prefixCls}-item-bottom-img`">
          <div class="img">
            <AsyncFile v-if="getFirstImage" :file="getFirstImage.id">
              <img
                slot-scope="props"
                :src="props.src"
                :alt="comment.user.name"
              >
            </AsyncFile>
            <AsyncFile v-if="getVideo" :file="getVideo">
              <img
                slot-scope="props"
                :src="props.src"
                :alt="comment.user.name"
              >
            </AsyncFile>
          </div>
          <div class="content">
            {{ comment.commentable.feed_content }}
          </div>
        </div>
      </section>
      <section v-if="comment.commentable === null">
        <div :class="`${prefixCls}-item-bottom-noImg`" class="content">
          动态已被删除
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import MessageCommentItemBase from './MessageCommentItemBase'

export default {
  name: 'MessageCommentFeedItem',
  extends: MessageCommentItemBase,
  computed: {
    getVideo () {
      const { comment } = this
      const { video } = comment.commentable
      if (video != null) {
        return video.cover_id
      } else {
        return false
      }
    },
  },
  methods: {
    goToDetail () {
      const {
        commentable: { id = 0 },
      } = this.comment
      this.$router.push(`/feeds/${id}`)
    },
    sendComment (comment) {
      const { commentable_id: feedId = 0, user_id: userId = 0 } = this.comment
      this.$http
        .post(
          `/feeds/${feedId}/comments`,
          {
            reply_user: userId,
            body: comment,
          },
          {
            validateStatus: s => s === 201,
          }
        )
        .then(() => {
          this.$Message.success('回复成功')
          this.$bus.$emit('commentInput:close', true)
        })
    },
  },
}
</script>
