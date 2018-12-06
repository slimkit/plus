<template>
  <div
    class="c-portal-panel"
    @mousedown="startDrag"
    @touchstart="startDrag"
    @mousemove.stop="onDrag"
    @touchmove.stop="onDrag"
    @mouseup="stopDrag"
    @touchend="stopDrag"
    @mouseleave="stopDrag"
  >
    <header :class="{ 'show-title': scrollTop > 1 / 2 * bannerHeight }" class="header">
      <div class="left">
        <svg class="m-style-svg m-svg-def" @click="onBackClick">
          <use xlink:href="#icon-back" />
        </svg>
        <CircleLoading v-show="updating" color="light" />
      </div>
      <div class="title m-text-cut">{{ title }}</div>
      <div class="right">
        <svg class="m-style-svg m-svg-def" @click="$emit('more')">
          <use xlink:href="#icon-more" />
        </svg>
      </div>
    </header>

    <div v-if="loading" class="m-pos-f m-spinner" />

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
        <div class="m-box m-aln-center m-justify-center load-more-box">
          <span v-if="noMore" class="load-more-ph">---没有更多---</span>
          <span
            v-else
            class="load-more-btn"
            @click.stop="$emit('loadmore')"
            v-text="fetching ? '加载中...' : '点击加载更多'"
          />
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
    cover: { type: String, default: '' },
    loading: { type: Boolean, default: false },
    showFooter: { type: Boolean, default: false },
    fetching: { type: Boolean, default: false },
    noMore: { type: Boolean, default: false },
    back: { type: Function, default: null },
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

      fetchFollow: false,
    }
  },
  computed: {
    bio () {
      return this.user.bio || '这家伙很懒,什么也没留下'
    },
    bannerStyle () {
      return [
        { 'background-image': this.cover ? `url("${this.cover}")` : undefined },
        this.paddingTop,
        { transitionDuration: this.dragging ? '0s' : '300ms' },
      ]
    },

    // banner 相关
    paddingTop () {
      return {
        paddingTop: ((this.bannerHeight + 80 * Math.atan(this.dY / 200)) / (this.bannerHeight * 2)) * 100 + '%',
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
    onUpdate () {
      this.updating = true
      this.dY = 0
    },
    afterUpdate () {
      this.updating = false
    },
    onScroll: _.debounce(function () {
      this.scrollTop = Math.max(
        0,
        document.body.scrollTop,
        document.documentElement.scrollTop
      )
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
      this.dY = 0
      if (this.dY > 300 && this.scrollTop <= 0) this.$emit('update')
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
    height: 90px;
    max-width: 768px;
    border-bottom: 0;
    background-color: transparent;
    color: #fff;
    font-size: 32px;
    transition: background 0.3s ease;
    overflow: hidden;
    z-index: 10;

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
      justify-content: space-around;
      align-items: center;
    }

    .left {
      width: 90*2px;
      padding-left: 30px;
      justify-content: flex-start;
    }
    .right {
      padding-right: 30px;
      justify-content: flex-end;
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
    padding-top: 320/640 * 100%;
    width: 100%;
    transform: translate3d(0, 0, 0);
    background-size: cover;
    background-position: center;
    background-image: url("../images/user_home_default_cover.png");
    font-size: 28px;
    color: #fff;
    text-shadow: 0 1px 1px rgba(0, 0, 0, 0.5); /* no */
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
}

</style>
