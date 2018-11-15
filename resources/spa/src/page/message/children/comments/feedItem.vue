<template>
  <div>
    <div :class="`${prefixCls}-item-top`">
      <avatar :user="user"/>
      <section class="userInfo">
        <router-link
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${comment.user_id}`">
          {{ comment.user.name }}
        </router-link>
        <span v-if="comment.reply_user"> 回复</span>
        <span v-else> 评论了你的动态</span>
        <router-link
          v-if="comment.reply_user"
          :class="`${prefixCls}-item-top-link`"
          :to="`/users/${comment.reply_user}`">{{ comment.reply.name }}
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
          v-if="!getFirstImage && !getVideo"
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content">
          {{ comment.commentable.feed_content }}
        </div>
        <div
          v-else
          :class="`${prefixCls}-item-bottom-img`">
          <div class="img">
            <async-file
              v-if="getFirstImage"
              :file="getFirstImage.id">
              <img
                slot-scope="props"
                :src="props.src"
                :alt="comment.user.name">
            </async-file>
            <async-file
              v-if="getVideo"
              :file="getVideo">
              <img
                slot-scope="props"
                :src="props.src"
                :alt="comment.user.name">
            </async-file>
          </div>
          <div class="content">
            {{ comment.commentable.feed_content }}
          </div>
        </div>
      </section>
      <section v-if="comment.commentable === null">
        <div
          :class="`${prefixCls}-item-bottom-noImg`"
          class="content">
          动态已被删除
        </div>
      </section>
    </div>
  </div>
</template>

<script>
const prefixCls = "msgList";
const url = "/feeds/";

export default {
  name: "FeedsItem",
  props: {
    comment: { type: Object, required: true }
  },
  data: () => ({
    prefixCls,
    url,
    title: "动态"
  }),
  computed: {
    /**
     * 获取图片,并计算地址
     * @Author   Wayne
     * @DateTime 2018-01-31
     * @Email    qiaobin@zhiyicx.com
     * @return   {false|Object}            [description]
     */
    getFirstImage() {
      const { comment } = this;
      const { length } = comment.commentable.images;
      if (length > 0) {
        const [img] = comment.commentable.images;

        return img;
      }

      return false;
    },
    getVideo() {
      const { comment } = this;
      const { video } = comment.commentable;
      if (video != null) {
        return video.cover_id;
      } else {
        return false;
      }
    },
    user() {
      const { user } = this.comment || { user: {} };
      return user;
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
      this.$router.push(`/feeds/${id}`);
    },

    sendComment(comment) {
      const { commentable_id: feedId = 0, user_id: userID = 0 } = this.comment;
      this.$http
        .post(
          `/feeds/${feedId}/comments`,
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
