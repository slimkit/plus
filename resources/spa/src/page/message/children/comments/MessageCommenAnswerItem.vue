<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <Avatar :user="user" />
      <section class="userInfo">
        <!-- eslint-disable-next-line vue/component-name-in-template-casing -->
        <i18n v-if="comment.reply_user" path="message.comment.reply">
          <RouterLink
            slot="user1"
            :class="`${prefixCls}-item-top-link`"
            :to="`/users/${comment.user_id}`"
          >
            {{ comment.user.name }}
          </RouterLink>
          <RouterLink
            slot="user2"
            :class="`${prefixCls}-item-top-link`"
            :to="`/users/${comment.reply_user}`"
          >
            {{ comment.reply.name }}
          </RouterLink>
        </i18n>
        <!-- eslint-disable-next-line vue/component-name-in-template-casing -->
        <i18n
          v-else
          path="message.comment.commented"
          :slot-scope="{type: $t('article.type.answer')}"
        >
          <RouterLink
            slot="user"
            :class="`${prefixCls}-item-top-link`"
            :to="`/users/${comment.user_id}`"
          >
            {{ comment.user.name }}
          </RouterLink>
        </i18n>
        <p>{{ comment.created_at | time2tips }}</p>
      </section>
      <section class="msgList-status">
        <section class="gray">
          <span class="reply" @click.stop="showCommentInput">
            {{ $t('reply.name') }}
          </span>
        </section>
      </section>
    </div>
    <div :class="`${prefixCls}-item-bottom`">
      <span class="content" @click.stop="showCommentInput">
        {{ comment.body }}
      </span>
      <section v-if="comment.commentable !== null" @click="goToDetail()">
        <div :class="`${prefixCls}-item-bottom-noImg`" class="content">
          {{ comment.commentable.body }}
        </div>
        <!-- <div :class="`${prefixCls}-item-bottom-img`" v-else>
          <img :src="getImage" :alt="comment.user.name">
          <div class="content">
            {{ comment.commentable.body }}
          </div>
        </div> -->
      </section>
      <section v-if="comment.commentable === null">
        <div :class="`${prefixCls}-item-bottom-noImg`" class="content">
          回答已被删除
        </div>
      </section>
    </div>
  </section>
</template>

<script>
import MessageCommentItemBase from './MessageCommentItemBase'

export default {
  name: 'MessageCommenAnswerItem',
  extends: MessageCommentItemBase,
  methods: {
    /**
     * 进入动态详情
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    goToDetail () {
      const {
        commentable: { id = 0 },
      } = this.comment
      this.$router.push(`/questions/${id}`)
    },

    sendComment (comment) {
      const { commentable_id: answerId = 0, user_id: userId = 0 } = this.comment
      this.$http
        .post(
          `/question-answers/${answerId}/comments`,
          { reply_user: userId, body: comment },
          { validateStatus: s => s === 201 }
        )
        .then(() => {
          this.$Message.success(this.$t('reply.success'))
          this.$bus.$emit('commentInput:close', true)
        })
    },
  },
}
</script>
