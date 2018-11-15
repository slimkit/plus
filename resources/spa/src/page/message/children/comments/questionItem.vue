<template>
  <section>
    <div :class="`${prefixCls}-item-top`">
      <avatar :user="user" />
      <section class="userInfo">
        <router-link
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${comment.user_id}`">{{ comment.user.name }}</router-link>
        <span v-if="comment.reply_user">回复</span><span v-else>评论了你的问题</span>
        <router-link
          v-if="comment.reply_user"
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${comment.reply_user}`">{{ comment.reply.name }} </router-link>:
        <p>{{ comment.created_at | time2tips }}</p>
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
          class="content">
          {{ comment.commentable.subject }}
        </div>
        <!-- <div :class="`${prefixCls}-item-bottom-img`" v-else>
          <img :src="getImage" :alt="comment.user.name">
          <div class="content">
            {{ comment.commentable.subject }}
          </div>
        </div> -->
      </section>
      <section v-if="comment.commentable === null">
        <div
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content">
          问题已被删除
        </div>
      </section>
    </div>
  </section>
</template>
<script>
const prefixCls = "msgList";
const url = "/questions/";
export default {
  name: "QuestionItem",
  props: {
    comment: { type: Object, default: () => {} }
  },
  data: () => ({
    prefixCls,
    url
  }),
  computed: {
    /**
     * 获取图片,并计算地址
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    getImage() {
      const { comment } = this;
      const { id = 0 } = comment.commentable.image || {};
      if (id > 0) {
        return `${this.$http.defaults.baseURL}/files/${id}`;
      }

      return false;
    }
  },
  methods: {
    /**
     * 进入动态详情
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    goToFeedDetail() {
      const {
        commentable: { id = 0 }
      } = this.comment;
      this.$router.push(`/questions/${id}`);
    },

    sendComment(comment) {
      const {
        commentable_id: questionId = 0,
        user_id: userID = 0
      } = this.comment;
      this.$http
        .post(
          `/questions/${questionId}/comments`,
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
