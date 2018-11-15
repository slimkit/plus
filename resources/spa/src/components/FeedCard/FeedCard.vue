<template>
  <div class="m-box-model m-card" @click="handleView('')">
    <div class="m-box">
      <div
        v-if="timeLine"
        class="m-box-model m-aln-center m-flex-grow0 m-flex-shrink0 m-card-time-line"
        v-html="timeLineText"/>
      <avatar v-else :user="user" />
      <section class="m-box-model m-flex-grow1 m-flex-shrink1 m-card-main">
        <header v-if="!timeLine" class="m-box m-aln-center m-justify-bet m-card-usr">
          <h4 class="m-flex-grow1 m-flex-shrink1">{{ user.name }}</h4>
          <div class="m-box m-aln-center">
            <span v-if="pinned" class="m-art-comment-icon-top">置顶</span>
            <span>{{ time | time2tips }}</span>
          </div>
        </header>
        <article class="m-card-body" @click="handleView('')">
          <h2 v-if="title">{{ title }}</h2>
          <div v-if="body.length > 0" class="m-card-con">
            <p
              :class="{needPay}"
              class="m-text-box m-text-cut-3"
              v-html="replaceURI(body)"/>
          </div>
          <feed-image
            v-if="images.length > 0"
            :id="feedID"
            :pics="images" />
          <feed-video
            v-if="video"
            :id="feedID"
            :video="video" />
        </article>
      </section>
    </div>
    <footer
      v-if="showFooter"
      class="m-box-model m-card-foot m-bt1"
      @click.stop>
      <div class="m-box m-aln-center m-card-tools m-lim-width">
        <a class="m-box m-aln-center" @click.prevent="handleLike">
          <svg class="m-style-svg m-svg-def">
            <use :xlink:href="liked ? '#icon-like' :'#icon-unlike'"/>
          </svg>
          <span>{{ likeCount | formatNum }}</span>
        </a>
        <a class="m-box m-aln-center" @click.prevent="handleComment">
          <svg class="m-style-svg m-svg-def">
            <use xlink:href="#icon-comment"/>
          </svg>
          <span>{{ commentCount | formatNum }}</span>
        </a>
        <a class="m-box m-aln-center" @click.prevent="handleView('')">
          <svg class="m-style-svg m-svg-def">
            <use xlink:href="#icon-eye"/>
          </svg>
          <span>{{ viewCount | formatNum }}</span>
        </a>
        <div class="m-box m-justify-end m-flex-grow1 m-flex-shrink1">
          <a class="m-box m-aln-center" @click.prevent="handleMore">
            <svg class="m-style-svg m-svg-def">
              <use xlink:href="#icon-more"/>
            </svg>
          </a>
        </div>
      </div>
      <ul v-if="commentCount > 0" class="m-card-comments">
        <li
          v-for="com in comments"
          v-if="com.id"
          :key="com.id">
          <comment-item :comment="com" @click="commentAction"/>
        </li>
      </ul>
      <div
        v-if="commentCount > 5"
        class="m-router-link"
        @click="handleView('comment_list')">
        <a>查看全部评论</a>
      </div>
    </footer>
  </div>
</template>

<script>
import { mapState } from "vuex";
import FeedImage from "./FeedImage.vue";
import FeedVideo from "./FeedVideo.vue";
import CommentItem from "./CommentItem.vue";
import { time2txt } from "@/filters.js";
import * as api from "@/api/feeds.js";

export default {
  name: "FeedCard",
  components: {
    FeedImage,
    CommentItem,
    FeedVideo
  },
  props: {
    timeLine: {
      type: Boolean,
      default: false
    },
    pinned: {
      type: Boolean,
      default: false
    },
    feed: {
      type: Object,
      required: true
    },
    showFooter: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    ...mapState(["CURRENTUSER"]),
    isMine() {
      return this.feed.user_id === this.CURRENTUSER.id;
    },
    feedID() {
      return this.feed.id;
    },
    comments: {
      get() {
        return this.feed.comments.slice(0, 5);
      },
      set(val) {
        this.feed.comments = val;
      }
    },
    liked: {
      get() {
        return !!this.feed.has_like;
      },
      set(val) {
        this.feed.has_like = val;
      }
    },
    likeCount: {
      get() {
        return this.feed.like_count || 0;
      },
      set(val) {
        this.feed.like_count = ~~val;
      }
    },
    commentCount: {
      get() {
        return this.feed.feed_comment_count || 0;
      },
      set(val) {
        this.feed.feed_comment_count = val;
      }
    },
    viewCount() {
      return this.feed.feed_view_count || 0;
    },
    time() {
      return this.feed.created_at;
    },
    user() {
      const user = this.feed.user;
      return user && user.id ? user : {};
    },
    needPay() {
      const { paid_node } = this.feed;
      return paid_node && !paid_node.paid;
    },
    images() {
      return this.feed.images || [];
    },
    video() {
      return this.feed.video || false;
    },
    body() {
      return this.feed.feed_content || "";
    },
    has_collect: {
      get() {
        return this.feed.has_collect;
      },
      set(val) {
        this.feed.has_collect = val;
      }
    },
    timeLineText() {
      const text = time2txt(this.time);
      const len = text.length;
      return len > 4
        ? `<span>${text.substr(0, len - 2)}</span><span>${text.substr(
            -2,
            2
          )}</span>`
        : `<span>${text}</span>`;
    },
    title() {
      return this.feed.title || "";
    }
  },
  mounted() {
    this.user && this.$store.commit("SAVE_USER", this.user);
  },
  methods: {
    replaceURI(str) {
      // 脚本内容以纯文本方式显示
      const scriptRegex = /<\s*script\s*>(.*?)<\s*\/\s*script\s*>/i;
      str = str.replace(scriptRegex, "&lt;script&gt;$1&lt;/script&gt;");

      // 换行符转换
      str = str.replace(/\n/g, "<br>");

      const reg = /(https?|http|ftp|file):\/\/[-A-Za-z0-9+&@#/%?=~_|!:,.;]+[-A-Za-z0-9+&@#/%=~_|]/g;
      return str
        ? str.replace(
            reg,
            link =>
              `<a class="m-art-links" href="${link}" onclick='event.stopPropagation()' target="__blank">#网页链接#</a>`
          )
        : "";
    },
    handleView(hash) {
      const path = hash
        ? `/feeds/${this.feedID}#${hash}`
        : `/feeds/${this.feedID}`;
      const { paid_node } = this.feed;
      paid_node && !paid_node.paid
        ? this.$lstore.hasData("H5_ACCESS_TOKEN")
          ? this.$bus.$emit("payfor", {
              onCancel: () => {},
              onSuccess: data => {
                this.$Message.success(data);
                this.$router.push(path);
              },
              nodeType: "内容",
              node: paid_node.node,
              amount: paid_node.amount
            })
          : this.$nextTick(() => {
              const path = this.$route.fullPath;
              this.$router.push({
                path: "/signin",
                query: { redirect: path }
              });
            })
        : this.$router.push(path);
    },
    handleLike() {
      const method = this.liked ? "delete" : "post";
      const url = this.liked
        ? `/feeds/${this.feedID}/unlike`
        : `/feeds/${this.feedID}/like`;
      if (this.fetching) return;
      this.fetching = true;
      this.$http({
        method,
        url,
        validateStatus: s => s === 201 || s === 204
      })
        .then(() => {
          method === "post"
            ? ((this.liked = true), (this.likeCount += 1))
            : ((this.liked = false), (this.likeCount -= 1));
          this.fetching = false;
        })
        .catch(() => {
          this.fetching = false;
        });
    },
    handleComment({ placeholder, reply_user }) {
      this.$bus.$emit("commentInput", {
        placeholder,
        onOk: text => {
          this.sendComment({ body: text, reply_user });
        }
      });
    },
    handleMore() {
      const actions = [];
      if (this.has_collect) {
        actions.push({
          text: "取消收藏",
          method: () => {
            api.uncollectFeed(this.feedID).then(() => {
              this.$Message.success("取消收藏");
              this.has_collect = false;
            });
          }
        });
      } else {
        actions.push({
          text: "收藏",
          method: () => {
            api.collectionFeed(this.feedID).then(() => {
              this.$Message.success("收藏成功");
              this.has_collect = true;
            });
          }
        });
      }
      if (this.isMine) {
        // 是否是自己文章的评论
        actions.push({
          text: "申请动态置顶",
          method: () => {
            this.popupBuyTS();
          }
        });
        actions.push({
          text: "删除动态",
          method: () => {
            setTimeout(() => {
              const actionSheet = [
                {
                  text: "删除",
                  style: { color: "#f4504d" },
                  method: () => {
                    api.deleteFeed(this.feedID).then(() => {
                      this.$Message.success("删除动态成功");
                      this.$nextTick(() => {
                        this.$el.remove();
                        this.$emit("afterDelete");
                      });
                    });
                  }
                }
              ];
              this.$bus.$emit("actionSheet", actionSheet, "取消", "确认删除?");
            }, 200);
          }
        });
      } else {
        actions.push({
          text: "举报",
          method: () => {
            this.$Message.info("举报功能开发中，敬请期待");
          }
        });
      }

      this.$bus.$emit("actionSheet", actions, "取消");
    },
    commentAction({ isMine = false, placeholder, reply_user, comment }) {
      if (isMine) {
        const isOwner = this.feed.user.id === this.CURRENTUSER.id;
        const actionSheet = [
          {
            text: isOwner ? "评论置顶" : "申请评论置顶",
            method: () => {
              this.popupBuyTS();
            }
          },
          { text: "删除评论", method: () => this.deleteComment(comment.id) }
        ];
        this.$bus.$emit("actionSheet", actionSheet);
      } else {
        this.handleComment({
          placeholder,
          reply_user
        });
      }
    },
    sendComment({ reply_user: replyUser, body }) {
      if (body && body.length === 0)
        return this.$Message.error("评论内容不能为空");

      const params = {
        body,
        reply_user: replyUser
      };
      api
        .postComment(this.feedID, params)
        .then(comment => {
          this.commentCount += 1;
          this.comments.unshift(comment);
          if (this.comments.length > 5) this.comments.pop();
          this.$Message.success("评论成功");
          this.$bus.$emit("commentInput:close", true);
        })
        .catch(() => {
          this.$bus.$emit("commentInput:close", true);
        });
    },
    deleteComment(commentId) {
      api.deleteFeedComment(this.feedID, commentId).then(() => {
        this.feed.comments = this.feed.comments.filter(c => c.id !== commentId);
        this.commentCount -= 1;
        this.$Message.success("删除评论成功");
      });
    }
  }
};
</script>

<style lang="less" scoped>
.m-card {
  padding: 30px 20px 0;
  box-sizing: border-box;
  background-color: #fff;

  &-time-line {
    width: 60px;
    font-size: 24px;
    text-align: center;
    span:last-child {
      order: -1;
      font-size: 44px;
    }
  }
  &-usr {
    font-size: 24px;
    color: #ccc;
    margin-bottom: 30px;
    h4 {
      color: #000;
      font-size: 26px;
    }
    span + span {
      margin-left: 15px;
    }
  }
  &-main {
    margin-left: 20px;
  }
  &-con {
    overflow: hidden;
    font-size: 30px;
    line-height: 42px;
    color: @text-color2;
    display: -webkit-box;
    margin-bottom: 20px;
    .needPay:after {
      content: " 付费节点，购买后方可查看原文详情 付费节点，购买后方可查看原文详情 付费节点，购买后方可查看原文详情";
      text-shadow: 0 0 10px @text-color2; /* no */
      color: rgba(255, 255, 255, 0);
      margin-left: 5px;
      // filter: DXImageTransform.Microsoft.Blur(pixelradius=2);
      zoom: 1;
    }
  }
  &-body {
    > h2 {
      font-size: 32px;
      font-weight: bold;
      margin-bottom: 20px;
    }
  }
  &-foot {
    margin-top: 20px;
    margin-left: -20px;
    margin-right: -20px;
    padding: 0 20px 0 120px;
  }
  &-tools {
    padding: 30px 0;
    color: #b3b3b3;
    font-size: 24px;
    a {
      color: inherit;
      + a {
        margin-left: 60px;
      }
    }
    span {
      margin-left: 10px;
    }
  }
  &-comments {
    margin-bottom: 30px;
    line-height: 42px;
    color: @text-color3;
    font-size: 26px;
  }
}

.m-router-link {
  a {
    color: inherit;
  }
  font-size: 26px;
  color: @text-color1;
  margin-top: -15px;
  margin-bottom: 30px;
}
</style>
