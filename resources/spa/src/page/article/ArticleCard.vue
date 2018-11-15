<template>
  <transition>
    <div class="c-article-card">
      <div class="m-box-model m-art-card">
        <header ref="head" class="m-box-model m-pos-f m-head-top">
          <slot name="head">
            <common-header>资讯详情</common-header>
          </slot>
        </header>

        <div v-if="loading" class="m-spinner pos-f">
          <div/>
          <div/>
        </div>

        <main class="m-box-model">
          <slot/>
        </main>

        <footer
          v-if="canOprate"
          ref="foot"
          class="m-pos-f">
          <slot name="foot">
            <a class="m-box-model m-aln-center" @click.prevent="handelLike">
              <svg class="m-style-svg m-svg-def">
                <use :xlink:href="liked ? '#icon-like' :'#icon-unlike'"/>
              </svg>
              <span>喜欢</span>
            </a>
            <a class="m-box-model m-aln-center" @click.prevent="handelComment">
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-comment"/>
              </svg>
              <span>评论</span>
            </a>
            <a class="m-box-model m-aln-center" @click.prevent="handelShare">
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-share"/>
              </svg>
              <span>分享</span>
            </a>
            <a class="m-box-model m-aln-center" @click.prevent="handelMore">
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-more"/>
              </svg>
              <span>更多</span>
            </a>
          </slot>
        </footer>

      </div>
    </div>
  </transition>
</template>

<script>
import HeadRoom from "headroom.js";

export default {
  name: "ArticleCard",
  props: {
    loading: {
      type: Boolean,
      default: true
    },
    liked: {
      type: Boolean,
      default: false
    },
    canOprate: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      headroom: null,
      footroom: null
    };
  },
  computed: {
    isWechat() {
      return this.$store.state.BROWSER.isWechat;
    }
  },
  watch: {
    canOprate(val) {
      if (!val) return;
      this.$nextTick(() => {
        if (!this.footroot)
          this.footroom = new HeadRoom(this.$refs.foot, {
            tolerance: 5,
            offset: 50,
            classes: {
              initial: "headroom-foot",
              pinned: "headroom--footShow",
              unpinned: "headroom--footHide"
            }
          });
        this.footroom.init();
      });
    }
  },
  mounted() {
    this.headroom = new HeadRoom(this.$refs.head, {
      tolerance: 5,
      offset: 50,
      classes: {
        initial: "headroom-head",
        pinned: "headroom--headShow",
        unpinned: "headroom--headHide"
      }
    });
    this.headroom.init();

    this.canOprate &&
      this.$nextTick(() => {
        this.footroom = new HeadRoom(this.$refs.foot, {
          tolerance: 5,
          offset: 50,
          classes: {
            initial: "headroom-foot",
            pinned: "headroom--footShow",
            unpinned: "headroom--footHide"
          }
        });

        this.footroom.init();
      });
  },
  methods: {
    handelLike() {
      this.$emit("on-like");
    },
    handelComment() {
      this.$emit("on-comment");
    },
    handelShare() {
      this.$emit("on-share");
    },
    handelMore() {
      this.$emit("on-more");
    },
    goback() {
      this.$router.go(-1);
    }
  }
};
</script>

<style lang="less" scoped>
.c-article-card {
  position: relative;
  height: 100%;

  .m-art-card {
    min-height: 100%;

    > header {
      display: flex;
      justify-content: center;
      margin: 0 auto;
      background-color: #fff;
    }

    > main {
      margin-top: 90px;
      margin-bottom: 90 + 12px;
    }

    > footer {
      display: flex;
      align-items: center;
      justify-content: space-around;
      top: initial;
      height: 95px;
      font-size: 24px;
      background-color: #fff;
      border-top: 1px solid @border-color;

      a {
        color: #b3b3b3;
      }

      span {
        margin-top: 4px;
      }
    }
  }
}
</style>
