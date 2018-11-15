<template>
  <div
    class="p-user-home"
    @mousedown="startDrag"
    @touchstart="startDrag"
    @mousemove.stop="onDrag"
    @touchmove.stop="onDrag"
    @mouseup="stopDrag"
    @touchend="stopDrag"
    @mouseleave="stopDrag">
    <header
      ref="head"
      :class="{ 'show-title': scrollTop > 1 / 2 * bannerHeight }"
      class="m-box m-lim-width m-pos-f m-head-top bg-transp">
      <div class="m-box m-flex-grow1 m-aln-center m-flex-base0">
        <svg class="m-style-svg m-svg-def white" @click="beforeBack">
          <use xlink:href="#icon-back"/>
        </svg>
        <circle-loading v-if="updating" />
      </div>
      <div class="m-box m-flex-grow1 m-aln-center m-flex-base0 m-justify-center m-trans-y">
        <span class="m-text-cut">{{ user.name }}</span>
      </div>
      <div class="m-box m-flex-grow1 m-aln-center m-flex-base0 m-justify-end">
        <!-- <svg class="m-style-svg m-svg-def">
          <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-more"></use>
        </svg> -->
      </div>
    </header>
    <div v-if="loading" class="m-pos-f m-spinner">
      <div/>
      <div/>
    </div>
    <!-- style="overflow-x: hidden; overflow-y:auto; min-height: 100vh" -->
    <main>
      <div
        ref="banner"
        :style="bannerStyle"
        class="m-urh-banner">
        <div class="m-box-model m-aln-center m-justify-end m-pos-f m-urh-bg-mask">
          <label v-if="isMine" class="banner-click-area">
            <input
              ref="imagefile"
              :accept="accept"
              type="file"
              class="m-rfile"
              @change="onBannerChange" >
          </label>
          <avatar :user="user" size="big" />
          <h3>{{ user.name }}</h3>
          <p>
            <router-link
              append
              to="followers"
              tag="span">粉丝<i>{{ followersCount | formatNum }}</i></router-link>
            <router-link
              append
              to="followings"
              tag="span">关注<i>{{ followingsCount | formatNum }}</i></router-link>
          </p>
        </div>
      </div>
      <div class="m-text-box m-urh-info">
        <p v-if="verified" class="m-cf94">
          认证：<span>{{ verified.description }}</span>
        </p>
        <p v-if="user.location">地址：<span>{{ user.location }}</span></p>
        <p>简介：<span>{{ bio }}</span></p>
        <p style="margin-top: 0; margin-left: -0.1rem">
          <i
            v-for="tag in tags"
            v-if="tag.id"
            :key="`tag-${tag.id}`"
            class="m-urh-tag">
            {{ tag.name }}
          </i>
        </p>
      </div>
      <div
        v-clickoutside="hidenFilter"
        class="m-box m-aln-center m-justify-bet m-urh-filter-box"
        @click="popupBuyTS">
        <span>{{ feedsCount }}条动态</span>
        <div v-if="isMine" class="m-box m-aln-center m-urh-filter">
          <span>{{ feedTypes[screen] }}</span>
          <svg class="m-style-svg m-svg-small">
            <use xlink:href="#icon-list"/>
          </svg>
        </div>
      </div>
      <ul class="m-urh-feeds">
        <li
          v-for="feed in feeds"
          v-if="feed.id"
          :key="`ush-${userID}-feed${feed.id}`">
          <feed-card
            :feed="feed"
            :time-line="true"
            @afterDelete="fetchUserInfo()" />
        </li>
      </ul>
      <div class="m-box m-aln-center m-justify-center load-more-box">
        <span v-if="noMoreData" class="load-more-ph">---没有更多---</span>
        <span
          v-else
          class="load-more-btn"
          @click.stop="fetchUserFeed(true)">
          {{ fetchFeeding ? "加载中..." : "点击加载更多" }}
        </span>
      </div>
    </main>
    <footer
      v-if="!isMine"
      ref="foot"
      class="m-box m-pos-f m-main m-bt1 m-user-home-foot">
      <div class="m-flex-grow0 m-flex-shrink0 m-box m-aln-center m-justify-center" @click="rewardUser">
        <svg class="m-style-svg m-svg-def">
          <use xlink:href="#icon-profile-integral"/>
        </svg>
        <span>打赏</span>
      </div>
      <div
        :class="{ c_59b6d7: relation.status !== 'unFollow' }"
        class="m-flex-grow0 m-flex-shrink0 m-box m-aln-center m-justify-center"
        @click="followUserByStatus(relation.status)">
        <svg class="m-style-svg m-svg-def">
          <use :xlink:href="relation.icon"/>
        </svg>
        <span>{{ relation.text }}</span>
      </div>
      <!-- `/chats/${user.id}` -->
      <div class="m-flex-grow0 m-flex-shrink0 m-box m-aln-center m-justify-center" @click="startSingleChat">
        <svg class="m-style-svg m-svg-def">
          <use xlink:href="#icon-comment"/>
        </svg>
        <span>聊天</span>
      </div>
    </footer>
  </div>
</template>

<script>
import _ from "lodash";
import * as uploadApi from "@/api/upload";
import { hashFile } from "@/util/SendImage.js";
import FeedCard from "@/components/FeedCard/FeedCard.vue";
import HeadRoom from "headroom.js";
import wechatShare from "@/util/wechatShare.js";

import { startSingleChat } from "@/vendor/easemob";
import { checkImageType } from "@/util/imageCheck.js";
import * as api from "@/api/user.js";

export default {
  name: "UserHome",
  directives: {
    clickoutside: {
      bind(el, binding) {
        function documentHandler(e) {
          if (el.contains(e.target)) {
            return false;
          }
          if (binding.expression) {
            binding.value(e);
          }
        }
        el.__vueClickOutside__ = documentHandler;
        document.addEventListener("click", documentHandler);
      },
      unbind(el) {
        document.removeEventListener("click", el.__vueClickOutside__);
        delete el.__vueClickOutside__;
      }
    }
  },
  components: {
    FeedCard
  },
  data() {
    return {
      preUID: 0,
      scrollTop: 0,
      bannerHeight: 0,
      loading: true,
      dY: 0,
      startY: 0,
      dragging: false,
      updating: false,

      accept: {
        type: [Array, String],
        default() {
          return [
            "image/gif",
            "image/jpeg",
            "image/webp",
            "image/jpg",
            "image/png",
            "image/bmp"
          ];
        }
      },

      typeFilter: null,
      showFilter: false,
      screen: "all",

      feeds: [],
      feedTypes: {
        all: "全部动态",
        paid: "付费动态",
        pinned: "置顶动态"
      },
      noMoreData: false,
      fetchFeeding: false,

      tags: [],
      appList: [
        "onMenuShareQZone",
        "onMenuShareQQ",
        "onMenuShareAppMessage",
        "onMenuShareTimeline"
      ],
      config: {
        appid: "",
        signature: "",
        timestamp: "",
        noncestr: ""
      },
      footroom: null,

      fetchFollow: false
    };
  },
  computed: {
    isWechat() {
      return this.$store.state.BROWSER.isWechat;
    },
    currentUser() {
      return this.$store.state.CURRENTUSER;
    },
    userID() {
      return ~~this.$route.params.userId;
    },
    user: {
      get() {
        return this.$store.getters.getUserById(this.userID, true) || {};
      },
      set(val) {
        this.$store.commit("SAVE_USER", Object.assign(this.user, val));
      }
    },
    bio() {
      return this.user.bio || "这家伙很懒,什么也没留下";
    },
    extra() {
      return this.user.extra || {};
    },
    isMine() {
      return this.userID === this.currentUser.id;
    },
    followersCount() {
      return this.extra.followers_count || 0;
    },
    followingsCount() {
      return this.extra.followings_count || 0;
    },
    feedsCount() {
      return this.extra.feeds_count || 0;
    },
    bannerStyle() {
      return [
        this.userBackGround,
        this.paddingTop,
        { transitionDuration: this.dragging ? "0s" : "300ms" }
      ];
    },
    userBackGround() {
      let ubg = this.user.bg && this.user.bg.url;
      return ubg ? { "background-image": `url("${ubg}")` } : {};
    },
    verified() {
      return this.user.verified;
    },

    // banner 相关
    paddingTop() {
      return {
        paddingTop:
          ((this.bannerHeight + 80 * Math.atan(this.dY / 200)) /
            (this.bannerHeight * 2)) *
            100 +
          "%"
      };
    },
    after() {
      const len = this.feeds.length;
      return len > 0 ? this.feeds[len - 1].id : "";
    },
    relation: {
      get() {
        const relations = {
          unFollow: {
            text: "关注",
            status: "unFollow",
            icon: `#icon-unFollow`
          },
          follow: {
            text: "已关注",
            status: "follow",
            icon: `#icon-follow`
          },
          eachFollow: {
            text: "互相关注",
            status: "eachFollow",
            icon: `#icon-eachFollow`
          }
        };
        const { follower, following } = this.user;
        return relations[
          follower && following
            ? "eachFollow"
            : follower
              ? "follow"
              : "unFollow"
        ];
      },

      set(val) {
        this.user.follower = val;
      }
    }
  },
  watch: {
    screen(val) {
      val && this.updateData();
    }
  },
  beforeMount() {
    if (this.isIosWechat) {
      this.reload(this.$router);
    }
  },
  mounted() {
    this.typeFilter = this.$refs.typeFilter;
    this.bannerHeight = this.$refs.banner.getBoundingClientRect().height;

    this.isMine ||
      ((this.footroom = new HeadRoom(this.$refs.foot, {
        tolerance: 5,
        offset: 50,
        classes: {
          initial: "headroom-foot",
          pinned: "headroom--footShow",
          unpinned: "headroom--footHide"
        }
      })),
      this.footroom.init());
  },
  activated() {
    this.preUID !== this.userID
      ? ((this.loading = true),
        (this.feeds = []),
        (this.tags = []),
        this.updateData())
      : setTimeout(() => {
          this.loading = false;
        }, 300);

    window.addEventListener("scroll", this.onScroll);

    if (this.isWechat) {
      // 微信分享
      const shareUrl =
        window.location.origin +
        process.env.BASE_URL.substr(0, process.env.BASE_URL.length - 1) +
        this.$route.fullPath;
      const signUrl =
        this.$store.state.BROWSER.OS === "IOS" ? window.initUrl : shareUrl;
      const avatar = this.user.avatar || {};
      wechatShare(signUrl, {
        title: this.user.name,
        desc: this.user.bio,
        link: shareUrl,
        imgUrl: avatar.url || ""
      });
    }

    this.preUID = this.userID;
  },
  deactivated() {
    this.loading = true;
    this.showFilter = false;
    window.removeEventListener("scroll", this.onScroll);
  },
  destroyed() {
    window.removeEventListener("scroll", this.onScroll);
  },
  methods: {
    beforeBack() {
      if (this.$route.query.from === "checkin") this.$bus.$emit("check-in");
      this.goBack();
    },
    /**
     * 发起单聊
     */
    startSingleChat() {
      startSingleChat(this.user).then(res => {
        this.$nextTick(() => {
          this.$router.push(`/chats/${res}`);
        });
      });
    },
    rewardUser() {
      this.popupBuyTS();
    },
    followUserByStatus(status) {
      if (!status || this.fetchFollow) return;
      this.fetchFollow = true;

      api
        .followUserByStatus({
          id: this.user.id,
          status
        })
        .then(follower => {
          this.relation = follower;
          this.fetchFollow = false;
        });
    },
    hidenFilter() {
      this.showFilter = false;
    },
    fetchUserInfo() {
      api.getUserInfoById(this.userID, true).then(user => {
        this.user = Object.assign(this.user, user);
        this.loading = false;
      });
    },
    fetchUserTags() {
      this.$http.get(`/users/${this.userID}/tags`).then(({ data = [] }) => {
        this.tags = data;
      });
    },

    fetchUserFeed(loadmore) {
      if (this.fetchFeeding) return;
      this.fetchFeeding = true;
      const params = {
        limit: 15,
        type: "users",
        user: this.userID
      };

      loadmore && (params.after = this.after);
      this.isMine && this.screen !== "all" && (params.screen = this.screen);

      this.$http
        .get("/feeds", {
          params
        })
        .then(({ data: { feeds = [] } }) => {
          this.feeds = loadmore ? [...this.feeds, ...feeds] : feeds;
          this.updating = false;
          this.fetchFeeding = false;
          this.noMoreData = feeds.length < params.limit;
        });
    },
    updateData() {
      this.updating = true;
      this.dY = 0;
      this.fetchUserInfo();
      this.fetchUserFeed();
      this.fetchUserTags();
    },
    onBannerChange() {
      const $input = this.$refs.imagefile;
      const file = $input.files[0];

      checkImageType([file])
        .then(() => {
          this.uploadFile(file)
            .then(async node => {
              await this.$http.patch("/user", { bg: node });
              this.$Message.success("更新个人背景成功！");
              this.fetchUserInfo();
            })
            .catch(({ response: { data } = {} }) => {
              this.$Message.error(data.message);
            });
        })
        .catch(() => {
          this.$Message.info("请上传正确格式的图片文件");
          $input.value = "";
        });
    },
    async uploadFile(file) {
      // 如果需要新文件存储方式上传
      const hash = await hashFile(file);
      const params = {
        filename: file.name,
        hash,
        size: file.size,
        mime_type: file.type || "image/png",
        storage: { channel: "public" }
      };
      const result = await uploadApi.createUploadTask(params);
      return uploadApi
        .uploadImage({
          method: result.method,
          url: result.uri,
          headers: result.headers,
          blob: file
        })
        .then(data => {
          return Promise.resolve(data.node);
        })
        .catch(() => {
          this.$Message.error("文件上传失败，请检查文件系统配置");
          return Promise.reject();
        });
    },
    onScroll: _.debounce(function() {
      this.scrollTop = Math.max(
        0,
        document.body.scrollTop,
        document.documentElement.scrollTop
      );
    }, 1000 / 60),
    startDrag(e) {
      e = e.changedTouches ? e.changedTouches[0] : e;
      if (this.scrollTop <= 0 && !this.updating) {
        this.startY = e.pageY;
        this.dragging = true;
      }
    },
    onDrag(e) {
      const $e = e.changedTouches ? e.changedTouches[0] : e;
      if (this.dragging && $e.pageY - this.startY > 0 && window.scrollY <= 0) {
        // 阻止 原生滚动 事件
        e.preventDefault();
        this.dY = $e.pageY - this.startY;
      }
    },
    stopDrag() {
      this.dragging = false;
      this.dY > 300 && this.scrollTop <= 0 ? this.updateData() : (this.dY = 0);
    }
  }
};
</script>

<style lang="less" scoped>
.white {
  color: #fff;
}
.m-user-home-foot {
  height: 90px;
  top: initial;
  bottom: 0;
  font-size: 30px;
  > div {
    width: 1/3 * 100%;
    + div {
      border-left: 1px solid @border-color; /*no*/
    }
  }
  .m-svg-def {
    width: 32px;
    height: 32px;
    margin: 0 10px;
  }
}
.m-urh-banner {
  padding-top: 320/640 * 100%;
  width: 100%;
  transform: translate3d(0, 0, 0);
  background-size: cover;
  background-position: center;
  background-image: url("../images/user_home_default_cover.png");
  font-size: 28px;
  color: #fff;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.5);
  h3 {
    margin: 20px 0;
    font-size: 32px;
  }
  p {
    margin: 0 0 30px 0;
    span + span {
      margin-left: 40px;
    }
    i {
      margin: 0 5px;
    }
  }
}
.m-urh-info {
  background-color: #fff;
  padding: 30px 20px;
  font-size: 26px;
  line-height: 36px;
  color: @text-color3;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1); /*no*/
  p + p {
    margin-top: 10px;
  }
}

.m-urh-tag {
  margin-top: 20px;
  margin-left: 10px;
  display: inline-block;
  padding: 5px 20px;
  font-size: 24px;
  background-color: rgba(102, 102, 102, 0.1);
  border-radius: 18px;
}
.m-urh-filter {
  position: relative;
  &-box {
    padding: 25px 20px;
    color: @text-color3;
    font-size: 26px;
    position: sticky;
    top: 88px;
    z-index: 9;
    background-color: #f4f5f6;
    .m-style-svg {
      margin-left: 20px;
    }
  }
  &-options {
    overflow: hidden;
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 9;
    min-width: 200px;
    border-radius: 8px;
    background-color: #fff;
    transform: translate3d(0, 25px, 0);
    box-shadow: 0 0 10px 0 rgba(221, 221, 221, 0.6); /*no*/
    li {
      padding: 25px 20px;
      font-size: 24px;
      color: @text-color3;
      & + li {
        border-top: 1px solid @border-color; /*no*/
      }
    }
  }
}

.m-urh-bg-mask:after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  z-index: -1;
  margin: auto;
  opacity: 0.7;
  background-image: linear-gradient(
    180deg,
    rgba(0, 0, 0, 0.95),
    rgba(0, 0, 0, 0) 40%,
    rgba(0, 0, 0, 0) 50%,
    rgba(0, 0, 0, 0.95)
  );
}

.m-urh-feeds {
  li + li {
    margin-top: 10px;
  }
}

.m-head-top {
  border-bottom: 0;
  padding: 0 20px;

  &.bg-transp {
    color: #fff;
    transition: background 0.3s ease;
    background-color: transparent;
  }
  &.show-title {
    background-image: none;
    background-color: #fff;
    border-bottom: 1px solid @border-color; /*no*/
    color: #000;
    .m-trans-y {
      transform: none;
    }
  }
  .m-trans-y {
    transform: translateY(100%);
    transition: transform 0.3s ease;
  }
}

.banner-click-area {
  display: block;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 1;
}
</style>
