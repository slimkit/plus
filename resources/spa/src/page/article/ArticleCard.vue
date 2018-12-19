<template>
  <Transition>
    <div class="c-article-card">
      <div class="m-box-model m-art-card">
        <header ref="head" class="m-box-model m-pos-f m-head-top">
          <slot name="head">
            <CommonHeader>{{ title }}</CommonHeader>
          </slot>
        </header>

        <div v-if="loading" class="m-spinner m-pos-f" />

        <main class="m-box-model">
          <slot name="main" />
        </main>

        <footer
          v-if="canOprate"
          ref="foot"
          class="m-pos-f"
        >
          <slot name="foot">
            <a class="m-box-model m-aln-center" @click.prevent="handelLike">
              <svg class="m-style-svg m-svg-def">
                <use :xlink:href="liked ? '#icon-like' :'#icon-unlike'" />
              </svg>
              <span :class="{liked}">喜欢</span>
            </a>
            <a class="m-box-model m-aln-center" @click.prevent="handelComment">
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-comment" />
              </svg>
              <span>评论</span>
            </a>
            <a class="m-box-model m-aln-center" @click.prevent="handelShare">
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-share" />
              </svg>
              <span>分享</span>
            </a>
            <a class="m-box-model m-aln-center" @click.prevent="handelMore">
              <svg class="m-style-svg m-svg-def">
                <use xlink:href="#icon-more" />
              </svg>
              <span>更多</span>
            </a>
          </slot>
        </footer>
      </div>
    </div>
  </Transition>
</template>

<script>
import HeadRoom from 'headroom.js'
import { mapState } from 'vuex'

const typeMap = {
  feed: { title: '动态' },
  news: { title: '资讯' },
  post: { title: '帖子' },
  answer: { title: '回答' },
}

export default {
  name: 'ArticleCard',
  props: {
    type: { type: String, required: true, validator: type => Object.keys(typeMap).includes(type) },
    article: { type: Number, required: true }, // 文章 ID
    loading: { type: Boolean, default: true }, // 加载中
    liked: { type: Boolean, default: false }, // 已点赞
    canOprate: { type: Boolean, default: true },
  },
  data () {
    return {
      headroom: null,
      footroom: null,
    }
  },
  computed: {
    ...mapState({
      CURRENTUSER: 'CURRENTUSER',
    }),
    factory () {
      return typeMap[this.type]
    },
    isWechat () {
      return this.$store.state.BROWSER.isWechat
    },
    title () {
      return `${this.factory.title}详情`
    },
  },
  watch: {
    canOprate (val) {
      if (!val) return
      this.$nextTick(() => {
        if (!this.footroot) {
          this.footroom = new HeadRoom(this.$refs.foot, {
            tolerance: 5,
            offset: 50,
            classes: {
              initial: 'headroom-foot',
              pinned: 'headroom--footShow',
              unpinned: 'headroom--footHide',
            },
          })
        }
        this.footroom.init()
      })
    },
  },
  mounted () {
    this.headroom = new HeadRoom(this.$refs.head, {
      tolerance: 5,
      offset: 50,
      classes: {
        initial: 'headroom-head',
        pinned: 'headroom--headShow',
        unpinned: 'headroom--headHide',
      },
    })
    this.headroom.init()

    this.canOprate &&
      this.$nextTick(() => {
        this.footroom = new HeadRoom(this.$refs.foot, {
          tolerance: 5,
          offset: 50,
          classes: {
            initial: 'headroom-foot',
            pinned: 'headroom--footShow',
            unpinned: 'headroom--footHide',
          },
        })

        this.footroom.init()
      })
  },
  methods: {
    handelLike () {
      this.$emit('like')
    },
    handelComment () {
      this.$emit('comment')
    },
    handelShare () {
      if (this.isWechat) this.$Message.success('请点击右上角微信分享😳')
      else this.$Message.success('请使用浏览器的分享功能😳')
    },
    handelMore () {
      this.$emit('more')
    },
  },
}
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
      border-top: 1px solid @border-color; /* no */

      a {
        color: #b3b3b3;
      }

      span {
        margin-top: 4px;
      }

      .liked {
        color: @error;
      }
    }
  }
}
</style>
