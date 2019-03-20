<template>
  <div class="c-message-comments-item">
    <Avatar :user="comment.sender" />
    <main>
      <div class="sender-info">
        <section class="user-info">
          <RouterLink class="username" :to="`/users/${comment.sender.id}`">
            {{ comment.sender.name }}
          </RouterLink>
          <span v-if="comment.reply_user"> {{ $t('reply.name') }}</span>
          <span v-else> {{ $t(`message.comment.type.${comment.commentable.type}`) }}</span>
          <RouterLink
            v-if="comment.reply_user"
            class="comment-item-top-link"
            :to="`/users/${comment.reply_user}`"
          >
            {{ comment.reply.name }}
          </RouterLink>
          <p class="time"><slot /></p>
        </section>

        <span class="reply" @click.stop="showCommentInput">{{ $t('reply.name') }}</span>
      </div>
      <div class="comment-item-bottom">
        <span class="content" @click.stop="showCommentInput">
          {{ comment.contents }}
        </span>
        <Component
          :is="componentMap[type]"
          :id="comment.commentable.id"
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

export default {
  name: 'MessageCommentsItem',
  components: {
    ReferenceFeed,
    ReferenceNews,
    ReferencePost,
  },
  props: {
    comment: { type: Object, required: true },
  },
  data () {
    return {
      componentMap,
    }
  },
  computed: {
    type () {
      return this.comment.commentable.type
    },
  },
  methods: {
    showCommentInput () {
      this.$bus.$emit('commentInput', {
        placeholder: `回复: ${this.comment.user.name}`,
        onOk: comment => {
          this.sendComment(comment)
        },
      })
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

<style lang="less" scoped>
.c-message-comments-item {
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
