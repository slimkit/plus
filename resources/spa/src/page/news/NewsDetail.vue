<template>
  <article-card
    :liked="liked"
    :loading="loading"
    :can-oprate="news.audit_status===0"
    @on-like="likeNews"
    @on-share="shareNews"
    @on-more="moreAction"
    @on-comment="commentNews">

    <common-header slot="head">资讯详情</common-header>

    <jo-load-more
      ref="loadmore"
      :auto-load="false"
      :show-bottom="false"
      @onRefresh="onRefresh">
      <div class="m-flex-shrink1 m-flex-grow1 m-art m-main">
        <section class="m-art-head">
          <h1>{{ news.title }}</h1>
          <p>
            <i class="m-art-cate">{{ cate }}</i>
            <span>来自 {{ news.from || '原创' }}</span>
          </p>
        </section>
        <p v-if="news.subject" class="m-art-subject">{{ news.subject }}</p>
        <div class="m-art-body markdown-body" v-html="body"/>
        <div class="m-box m-aln-center m-justify-bet m-art-foot">
          <div class="m-flex-grow1 m-flex-shrink1 m-box m-aln-center m-art-like-list">
            <template v-if="likeCount > 0 && news.audit_status===0">
              <ul class="m-box m-flex-grow0 m-flex-shrink0">
                <li
                  v-for="({user, id}, index) in likes.slice(0, 5)"
                  :key="id"
                  :style="{ zIndex: 5-index }"
                  class="m-avatar-box tiny">
                  <img :src="getAvatar(user.avatar)">
                </li>
              </ul>
              <span>{{ likeCount | formatNum }}人点赞</span>
            </template>
          </div>
          <div class="m-box-model m-aln-end m-art-info">
            <span>发布于{{ time | time2tips }}</span>
            <span>{{ news.hits || 0 | formatNum }}浏览</span>
          </div>
        </div>
        <div class="m-box-model m-box-center m-box-center-a m-art-reward">
          <button class="m-art-rew-btn" @click="rewardNews">打 赏</button>
          <p class="m-art-rew-label">
            <a href="javascript:;">{{ reward.count | formatNum }}</a>人打赏，共
            <a href="javascript:;">{{ ~~reward.amount | formatNum }}</a>{{ currencyUnit }}
          </p>
          <router-link
            tag="ul"
            to="rewarders"
            append
            class="m-box m-aln-center m-art-rew-list">
            <li
              v-for="rew in rewardList"
              :key="rew.id"
              :class="`m-avatar-box-${rew.user.sex}`"
              class="m-flex-grow0 m-flex-shrink0 m-art-rew m-avatar-box tiny">
              <img :src="getAvatar(rew.user.avatar)">
            </li>
            <li v-if="rewardList.length > 0" class="m-box m-aln-center">
              <svg class="m-style-svg m-svg-def" style="fill:#bfbfbf">
                <use xlink:href="#icon-arrow-right"/>
              </svg>
            </li>
          </router-link>
        </div>
      </div>

      <div class="m-box-model m-art-comments">
        <ul class="m-box m-aln-center m-art-comments-tabs">
          <li>{{ commentCount | formatNum }}条评论</li>
        </ul>
        <template v-if="news.audit_status === 0">
          <comment-item
            v-for="(comment) in pinnedCom"
            :key="`pinned-${comment.id}`"
            :comment="comment"
            :pinned="true"
            @click="replyComment" />
          <comment-item
            v-for="(comment) in comments"
            :key="`comment-${comment.id}`"
            :comment="comment"
            @click="replyComment" />
          <div class="m-box m-aln-center m-justify-center load-more-box">
            <span v-if="noMoreCom" class="load-more-ph">---没有更多---</span>
            <span
              v-else
              class="load-more-btn"
              @click.stop="fetchNewsComments(maxComId)">
              {{ fetchComing ? "加载中..." : "点击加载更多" }}
            </span>
          </div>
        </template>
      </div>
    </jo-load-more>
  </article-card>
</template>

<script>
import { mapState } from "vuex";
import ArticleCard from "@/page/article/ArticleCard.vue";
import CommentItem from "@/page/article/ArticleComment.vue";
import wechatShare from "@/util/wechatShare.js";
import md from "@/util/markdown.js";
import { limit } from "@/api";
import * as api from "@/api/news.js";
import { noop } from "@/util";

export default {
  name: "NewsDetail",
  components: {
    ArticleCard,
    CommentItem
  },
  data() {
    return {
      oldID: 0,
      news: {},
      loading: true,
      fetching: false,

      likes: [],
      comments: [],
      rewardList: [],
      reward: {
        count: 0,
        amount: 0
      },
      pinnedCom: [],

      fetchComing: false,
      noMoreCom: false,
      maxComId: 0,
      config: {
        appid: "",
        signature: "",
        timestamp: "",
        noncestr: ""
      },
      appList: [
        "onMenuShareQZone",
        "onMenuShareQQ",
        "onMenuShareAppMessage",
        "onMenuShareTimeline"
      ],
      share: {
        title: "",
        desc: "",
        link: ""
      }
    };
  },
  computed: {
    ...mapState(["CURRENTUSER"]),
    firstImage() {
      let images = this.news.image;
      if (!Object.keys(images).length) {
        return "";
      }
      return (
        this.$http.defaults.baseURL + "/files/" + images.id + "?w=300&h=300"
      );
    },
    newsID() {
      return this.$route.params.newsID;
    },
    userID() {
      return this.news.user_id || 0;
    },
    isMine() {
      return this.news.user_id === this.CURRENTUSER.id;
    },
    liked: {
      get() {
        return !!this.news.has_like;
      },
      set(val) {
        this.news.has_like = val;
      }
    },
    likeCount: {
      get() {
        return this.news.digg_count || 0;
      },
      set(val) {
        val && (this.news.digg_count = val);
      }
    },
    commentCount: {
      get() {
        return this.news.comment_count || 0;
      },
      set(val) {
        this.news.comment_count = val;
      }
    },
    time() {
      return this.news.created_at || "";
    },
    cate() {
      const { category: { name = "未分类" } = {} } = this.news;
      return name;
    },
    body() {
      return md(this.news.content || "");
    },
    isWechat() {
      return this.$store.state.BROWSER.isWechat;
    },
    has_collect: {
      get() {
        return this.news.has_collect;
      },
      set(val) {
        this.news.has_collect = val;
      }
    }
  },
  beforeMount() {
    if (this.isIosWechat) {
      this.$Message.info("reload");
      this.reload(this.$router);
    }
  },
  activated() {
    if (this.newsID) {
      if (this.newsID !== this.oldID) {
        this.fetchNews();
      } else {
        setTimeout(() => {
          this.loading = false;
        }, 600);
      }
    }
  },
  deactivated() {
    this.loading = true;
  },
  methods: {
    shareSuccess() {
      this.$Message.success("分享成功");
    },
    shareCancel() {
      this.$Message.success("取消分享");
    },
    fetchNews(callback = noop) {
      if (this.fetching) return;
      this.fetching = true;
      api
        .getNewsById(this.newsID)
        .then(({ data = {} }) => {
          this.loading = false;
          this.fetching = false;
          this.news = data;
          this.oldID = this.newsID;
          this.share.title = data.title;
          this.share.desc = data.subject;
          this.fetchNewsComments();
          this.fetchNewsLikes();
          this.fetchRewardInfo();
          this.fetchRewards();
          callback();
          if (this.isWechat) {
            const shareUrl =
              window.location.origin +
              process.env.BASE_URL.substr(0, process.env.BASE_URL.length - 1) +
              this.$route.fullPath;
            const signUrl =
              this.$store.state.BROWSER.OS === "IOS"
                ? window.initUrl
                : shareUrl;
            wechatShare(signUrl, {
              title: data.title,
              desc: data.subject,
              link: shareUrl,
              imgUrl: this.firstImage
            });
          }
        })
        .catch(() => {
          this.$router.back();
        });
    },
    fetchNewsLikes() {
      // GET /news/{news}/likes
      this.$http.get(`/news/${this.newsID}/likes`).then(({ data = [] }) => {
        data && data.length, (this.likes = data);
      });
    },
    fetchNewsComments(after = 0) {
      if (this.fetchComing) return;
      this.fetchComing = true;

      api
        .getNewsComments(this.newsID, { after })
        .then(({ data: { pinneds = [], comments = [] } }) => {
          if (!after) {
            this.pinnedCom = pinneds;
            // 过滤第一页中的置顶评论
            const pinnedIds = pinneds.map(p => p.id);
            this.comments = comments.filter(c => pinnedIds.indexOf(c.id) < 0);
          } else {
            this.comments = [...this.comments, ...comments];
          }

          if (comments.length) {
            this.maxComId = comments[comments.length - 1].id;
          }

          this.noMoreCom = comments.lenght !== limit;
          this.fetchComing = false;
        })
        .catch(() => {
          this.fetchComing = false;
        });
    },
    fetchRewards() {
      api.getNewsRewards(this.newsID, { limit: 10 }).then(({ data = {} }) => {
        this.rewardList = data;
      });
    },
    fetchRewardInfo() {
      api.getRewardInfo(this.newsID).then(({ data = {} }) => {
        this.reward = {
          count: ~~data.count || 0,
          amount: ~~data.amount || 0
        };
      });
    },
    rewardNews() {
      this.popupBuyTS();
    },
    likeNews() {
      // DELETE /news/{news}/likes
      const method = this.liked ? "delete" : "post";
      if (this.fetching) return;
      this.fetching = true;
      this.$http({
        method,
        url: `/news/${this.newsID}/likes`,
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
    commentNews() {
      this.$bus.$emit("commentInput", {
        onOk: text => {
          this.sendComment({ body: text });
        }
      });
    },
    shareNews() {
      if (this.isWechat) this.$Message.success("请点击右上角微信分享😳");
      else this.$Message.success("请使用浏览器的分享功能😳");
    },
    moreAction() {
      const defaultActions = [];
      if (this.has_collect) {
        defaultActions.push({
          text: "取消收藏",
          method: () => {
            api.uncollectNews(this.newsID).then(() => {
              this.$Message.success("取消成功");
              this.has_collect = false;
            });
          }
        });
      } else {
        defaultActions.push({
          text: "收藏",
          method: () => {
            api.collectionNews(this.newsID).then(() => {
              this.$Message.success("收藏成功");
              this.has_collect = true;
            });
          }
        });
      }

      const actions = this.isMine
        ? [
            {
              text: "申请文章置顶",
              method: () => {
                this.$bus.$emit("applyTop", {
                  type: "news",
                  api: api.applyTopNews,
                  payload: this.newsID
                });
              }
            },
            {
              text: "删除",
              method: () => {
                this.$Message.info("资讯删除功能开发中，敬请期待");
              }
            }
          ]
        : [
            {
              text: "举报",
              method: () => {
                this.$Message.info("举报功能开发中，敬请期待");
              }
            }
          ];
      this.$bus.$emit("actionSheet", [...defaultActions, ...actions], "取消");
    },
    replyComment(uid, uname, commentId) {
      // 是否是自己的评论
      if (uid === this.CURRENTUSER.id) {
        // 是否是自己文章的评论
        const isOwner = uid === this.userID;
        const actionSheet = [
          {
            text: isOwner ? "评论置顶" : "申请评论置顶",
            method: () => {
              this.$bus.$emit("applyTop", {
                isOwner,
                type: "newsComment",
                api: api.applyTopNewsComment,
                payload: { newsId: this.newsID, commentId },
                callback: this.fetchNewsComments
              });
            }
          },
          { text: "删除评论", method: () => this.deleteComment(commentId) }
        ];
        this.$bus.$emit("actionSheet", actionSheet, "取消");
      } else {
        this.$bus.$emit("commentInput", {
          placeholder: `回复： ${uname}`,
          onOk: text => {
            this.sendComment({ reply_user: uid, body: text });
          }
        });
      }
    },
    sendComment({ reply_user: replyUser, body }) {
      const params = {};
      if (body && body.length > 0) {
        params.body = body;
        replyUser && (params["reply_user"] = replyUser);
        this.$http
          .post(`/news/${this.newsID}/comments`, params, {
            validateStatus: s => s === 201
          })
          .then(() => {
            this.$Message.success("评论成功");
            this.fetchNewsComments();
            this.commentCount += 1;
            this.$bus.$emit("commentInput:close", true);
          })
          .catch(() => {
            this.$Message.error("评论失败");
            this.$bus.$emit("commentInput:close", true);
          });
      } else {
        this.$Message.error("评论内容不能为空");
      }
    },
    deleteComment(commentId) {
      api.deleteNewsComment(this.newsID, commentId).then(() => {
        this.fetchNewsComments();
        this.commentCount -= 1;
        this.$Message.success("删除评论成功");
      });
    },
    onRefresh() {
      this.fetchNews(() => {
        this.$refs.loadmore.afterRefresh(true);
      });
    },
    getAvatar(avatar) {
      avatar = avatar || {};
      return avatar.url || null;
    }
  }
};
</script>

<style lang="less" scoped>
.m-art-head {
  padding-top: 36px;

  > h1 {
    margin-top: 0;
  }
}

.m-main {
  padding-bottom: 36px;
}
</style>
