<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <Avatar :user="user" />
      <section class="userInfo">
        <RouterLink :class="`${prefixCls}-item-top-link`" :to="`/users/${comment.user_id}`">
          {{ comment.user.name }}
        </RouterLink>
        <span v-if="comment.reply_user">回复</span>
        <span v-else>评论了你的回答</span>
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
          this.$Message.success('回复成功')
          this.$bus.$emit('commentInput:close', true)
        })
    },
  },
}
</script>
