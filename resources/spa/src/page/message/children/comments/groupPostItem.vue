<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <avatar :user="user" />
      <section class="userInfo">
        <router-link
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${comment.user_id}`">{{ comment.user.name }}</router-link>
        <span v-if="comment.reply_user"> 回复</span>
        <span v-else> 评论了你的帖子</span>
        <router-link
          v-if="comment.reply_user"
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${comment.reply_user}`"
        >
          {{ comment.reply.name }}
        </router-link>
        <p>{{ comment.created_at | time2tips }}</p>
      </section>
      <section class="msgList-status">
        <section class="gray">
          <span
            class="replay"
            @click.stop="showCommentInput">回复</span>
        </section>
      </section>
    </div>
    <div :class="`${prefixCls}-item-bottom`">
      <span
        class="content"
        @click.stop="showCommentInput">
        {{ comment.body }}
      </span>
      <section
        v-if="comment.commentable !== null"
        @click="goToFeedDetail()">
        <div
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content" >
          {{ comment.commentable.title }}
        </div>
        <!-- <div :class="`${prefixCls}-item-bottom-img`" v-else>
          <img :src="getImage" :alt="comment.user.name">
          <div class="content">
            {{ comment.commentable.body }}
          </div>
        </div> -->
      </section>
      <section v-if="comment.commentable === null">
        <div
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content">
          帖子已被删除
        </div>
      </section>
    </div>
  </section>
</template>
<style lang="less">
.gray {
  span.replay {
    background-color: #f3f4f4;
    padding: 10px 15px;
    color: #999;
    margin-right: 0;
  }
}
</style>
<script>
const prefixCls = "msgList";
export default {
  name: "GroupPostItem",
  props: {
    comment: { type: Object, default: () => {} }
  },
  data: () => ({
    prefixCls
  }),
  computed: {
    user() {
      const { user } = this.comment || { user: {} };
      return user;
    }
  },
  methods: {
    /**
     * 进入详情
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    goToFeedDetail() {
      const {
        commentable: { id = 0, group_id: groupId = 0 }
      } = this.comment;
      this.$router.push(`/groups/${groupId}/posts/${id}`);
    },

    sendComment(comment) {
      const { commentable_id: postId = 0, user_id: userID = 0 } = this.comment;
      this.$http
        .post(
          `/group-posts/${postId}/comments`,
          {
            reply_user: userID,
            body: comment
          },
          {
            validateStatus: s => s === 201
          }
        )
        .then(() => {
          this.$Message.success("回复成功");
          this.$bus.$emit("commentInput:close", true);
        });
    },
    /**
     * 调起输入框
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    showCommentInput() {
      this.$bus.$emit("commentInput", {
        placeholder: `回复: ${this.comment.user.name}`,
        onOk: comment => {
          this.sendComment(comment);
        }
      });
    }
  }
};
</script>
