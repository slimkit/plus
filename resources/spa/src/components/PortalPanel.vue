<template>
  <div
    class="c-portal-panel"
    :class="{'no-cover': !cover, loading}"
    @mousedown="startDrag"
    @touchstart="startDrag"
    @mousemove.stop="onDrag"
    @touchmove.stop="onDrag"
    @mouseup="stopDrag"
    @touchend="stopDrag"
    @mouseleave="stopDrag"
  >
    <div v-if="loading" class="m-pos-f m-spinner" />
    <header :class="{ 'show-title': scrollTop > 1 / 2 * bannerHeight }" class="header">
      <CircleLoading
        v-show="updating"
        class="fetching"
        :color="cover ? 'light': 'dark'"
      />
      <div class="left">
        <svg class="m-style-svg m-svg-def" @click="onBackClick">
          <use xlink:href="#icon-back" />
        </svg>
      </div>
      <div class="title m-text-cut">{{ title }}</div>
      <div :style="{opacity: loading ? 0 : 1}" class="right">
        <svg
          v-if="showMore"
          class="m-style-svg m-svg-def"
          @click="$emit('more')"
        >
          <use xlink:href="#icon-more" />
        </svg>
      </div>
    </header>

    <main>
      <div
        ref="banner"
        :style="bannerStyle"
        class="banner"
      >
        <slot name="head" />
      </div>

      <slot name="info" />

      <div class="sticky-bar">
        <slot name="sticky" />
      </div>

      <slot name="main" />

      <slot name="loadmore">
        <div v-if="noData" class="m-no-content" />
        <div v-else class="m-box m-aln-center m-justify-center load-more-box">
          <template v-if="noMore">
            <slot name="noMore">
              <span class="load-more-ph">{{ $t('loadmore.bottom[1]') }}</span>
            </slot>
          </template>
          <span
            v-else
            class="load-more-btn"
            @click.stop="$emit('loadmore')"
          >
            {{ fetching ? 0 : 2 | t('loadmore.bottom') }}
          </span>
        </div>
      </slot>
    </main>

    <footer
      v-if="showFooter"
      ref="foot"
      class="panel-footer"
    >
      <slot name="foot" />
    </footer>
  </div>
</template>

<script>
import _ from 'lodash'
import HeadRoom from 'headroom.js'

export default {
  name: 'PortalPanel',
  props: {
    title: { type: String, default: '' },
    cover: { type: [String, Boolean], default: '' },
    loading: { type: Boolean, default: false },
    showFooter: { type: Boolean, default: false },
    back: { type: Function, default: null },
    noData: { type: Boolean, default: true },
    showMore: { type: Boolean, default: true },
  },
  data () {
    return {
      preUID: 0,
      scrollTop: 0,
      bannerHeight: 0,
      dY: 0,
      startY: 0,
      dragging: false,
      updating: false,
      tags: [],
      footroom: null,
      noMore: false,
      fetching: false,
    }
  },
  computed: {
    bannerStyle () {
      const style = [
        this.paddingTop,
        { transitionDuration: this.dragging ? '0s' : '300ms' },
      ]
      if (this.cover) {
        style.push({ 'background-image': this.cover ? `url("${this.cover}")` : undefined })
      }
      return style
    },

    // banner 相关
    paddingTop () {
      return {
        paddingTop: ((this.bannerHeight + 80 * Math.atan(this.dY / 200)) / (this.bannerHeight * (this.cover ? 2 : 3))) * 100 + '%',
      }
    },
  },
  mounted () {
    this.typeFilter = this.$refs.typeFilter
    this.bannerHeight = this.$refs.banner.getBoundingClientRect().height
    if (this.showFooter) {
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
    }
    window.addEventListener('scroll', this.onScroll)
  },
  activated () {
    window.addEventListener('scroll', this.onScroll)
  },
  deactivated () {
    this.showFilter = false
    window.removeEventListener('scroll', this.onScroll)
  },
  destroyed () {
    window.removeEventListener('scroll', this.onScroll)
  },
  methods: {
    onBackClick () {
      if (this.back) this.back()
      else this.goBack()
    },
    beforeUpdate () {
      this.updating = true
      this.$emit('update')
    },
    afterUpdate () {
      this.updating = false
    },
    beforeLoadMore () {
      this.fetching = true
    },
    afterLoadMore (noMore = true) {
      this.noMore = noMore
      this.fetcing = false
    },
    onScroll: _.debounce(function () {
      const scrollTop = document.body.scrollTop || document.documentElement.scrollTop
      this.scrollTop = Math.max(0, scrollTop)
    }, 1000 / 60),
    startDrag (e) {
      e = e.changedTouches ? e.changedTouches[0] : e
      if (this.scrollTop <= 0 && !this.updating) {
        this.startY = e.pageY
        this.dragging = true
      }
    },
    onDrag (e) {
      const $e = e.changedTouches ? e.changedTouches[0] : e
      if (this.dragging && $e.pageY - this.startY > 0 && window.scrollY <= 0) {
        // 阻止 原生滚动 事件
        e.preventDefault()
        this.dY = $e.pageY - this.startY
      }
    },
    stopDrag () {
      this.dragging = false
      if (this.dY > 300 && this.scrollTop <= 0) {
        this.beforeUpdate()
      }
      this.dY = 0
    },
  },
}
</script>

<style lang="less" scoped>
.c-portal-panel {
  .header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: initial;
    display: flex;
    justify-content: space-between;
    height: 90px;
    max-width: 768px;
    border-bottom: 0;
    margin: -1px auto 0;/*no*/
    background-color: transparent;
    color: #fff;
    font-size: 32px;
    transition: background 0.3s ease;
    overflow: hidden;
    z-index: 30;

    .c-loading {
      position: absolute;
      left: 90px;
      width: 60px;
      height: 90px;
    }

    .title {
      display: flex;
      align-items: center;
      justify-content: center;
      flex: auto;
      min-width: 0;
      transition: transform 0.3s ease;
      transform: translateY(100%);
    }

    .left,
    .right {
      flex: none;
      width: 90px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    &.show-title {
      background-image: none;
      background-color: #fff;
      border-bottom: 1px solid @border-color; /*no*/
      color: #000;

      .title {
        transform: none;
      }
    }
  }

  .banner {
    position: relative;
    width: 100%;
    padding-top: (320/640 * 100%);
    background: #fff center/cover no-repeat;
    font-size: 28px;
    color: #fff;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.5); /* no */
    transform: translate3d(0, 0, 0);
  }

  .sticky-bar {
    position: sticky;
    top: 88px;
    background-color: #f4f5f6;
    color: @text-color3;
    font-size: 26px;
    z-index: 9;
  }

  .panel-footer {
    position: fixed;
    left: 0;
    right: 0;
    top: initial;
    bottom: 0;
    display: flex;
    justify-content: space-around;
    height: 90px;
    font-size: 30px;
    border-top: 1px solid @border-color;
    background-color: #fff;
  }

  &.loading {
    .header {
      color: #333;
    }
    > main {
      filter: blur(30px);
    }
  }

  &.no-cover {
    .header {
      color: #333;
    }
    .banner {
      color: #333;
      text-shadow: none;
      h1 {
        font-weight: bold;
      }
      p {
        color: @text-color3;
      }
    }
  }
}

</style>
