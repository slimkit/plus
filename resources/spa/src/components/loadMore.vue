<template>
  <div
    :style="{ transform: translate, transitionDuration: transition }"
    @mousedown="startDrag"
    @touchstart="startDrag"
    @mousemove.stop="onDrag"
    @touchmove.stop="onDrag"
    @mouseup="stopDrag"
    @touchend="stopDrag"
    @mouseleave="stopDrag" >
    <div class="load-more-tips">
      <slot name="head">
        <div
          v-if="requesting"
          class="load-more-loading">
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
        </div>
        <i
          v-else
          :class="{up: (dragging && dY > topDistance)}">↓</i>
        <span v-if="showText">{{ status }}</span>
      </slot>
    </div>
    <slot/>
    <div
      v-if="onLoadMore"
      class="load-more-tips">
      <template v-if="loading">
        <div class="load-more-loading">
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
          <span/>
        </div>
        <span>加载中...</span>
      </template>
      <template v-if="noMore && !loading">
        <span>没有更多数据了</span>
      </template>
    </div>
  </div>
</template>

<script>
import _ from "lodash";

const getScrollTarget = el => {
  while (
    el &&
    el.nodeType === 1 &&
    el.tagName !== "HTML" &&
    el.tagName !== "BODY"
  ) {
    const overflowY = document.defaultView.getComputedStyle(el).overflowY;
    if (overflowY === "scroll" || overflowY === "auto") {
      return el;
    }
    el = el.parentNode;
  }
  return document.documentElement;
};

export default {
  name: "LoadMore",
  props: {
    noTranslateAnimation: {
      type: Boolean,
      default: false
    },
    topDistance: {
      type: Number,
      default: 100
    },
    topPullText: {
      type: String,
      default: "下拉刷新"
    },
    topDropText: {
      type: String,
      default: "刷新中..."
    },
    topLoadingText: {
      type: String,
      default: "释放更新"
    },
    showText: {
      type: Boolean,
      default: !0
    },
    onRefresh: {
      type: Function,
      default: function() {
        setTimeout(() => {
          this.topEnd(false);
        }, 2000);
      }
    },
    onLoadMore: {
      type: Function,
      default: null
    }
  },
  data() {
    return {
      scrollTarget: null,
      topBarHeight: 0,
      requesting: !1,
      loading: !1,
      dragging: false,
      startY: 0,
      dY: 0,
      reset: true,
      noMore: false,
      reached: false,

      bottom: 0
    };
  },
  computed: {
    canPulldown() {
      return typeof this.onRefresh === "function";
    },
    scEl() {
      return this.scrollTarget === document.documentElement
        ? window
        : this.scrollTarget;
    },
    visibleHeight() {
      return this.scrollTarget.clientHeight * 1.325;
    },
    distance() {
      return 0.05 * this.visibleHeight;
    },
    transition() {
      return this.dragging || (this.dY === 0 && this.reset) ? "0s" : "200ms";
    },
    translate() {
      return !this.noTranslateAnimation
        ? "translateY(" +
            (80 * Math.atan(this.dY / 200) - this.topBarHeight) +
            "px)"
        : "";
    },
    status() {
      return this.dragging && this.dY > this.topDistance
        ? this.topLoadingText
        : this.requesting
          ? this.topDropText
          : this.topPullText;
    }
  },
  watch: {
    loading(val) {
      val
        ? this.scEl.removeEventListener("scroll", this.handleScrolling)
        : this.scEl.addEventListener("scroll", this.handleScrolling);
    },
    requesting(val) {
      val || (this.dY = 0);
    }
  },
  mounted() {
    this.scrollTarget = getScrollTarget(this.$el);
    this.topBarHeight = this.$el.children[0].clientHeight;
    this.bindEvent();

    this.autoLoad();
  },
  activated() {
    this.$nextTick(this.bindEvent);
  },
  deactivated() {
    this.scEl.removeEventListener("scroll", this.handleScrolling);
  },
  destroyed() {
    this.scEl.removeEventListener("scroll", this.handleScrolling);
  },
  methods: {
    /**
     * Handle scroll.
     *
     * @return {Function}
     * @author Seven Du <shiweidu@outlook.com>
     */
    handleScrolling: _.throttle(function() {
      if (this.noMore) return;
      if (this.loading) return;
      if (!this.bottomReached()) return;
      // 加载...
      this.loading = true;
      this.onLoadMore();
    }, 300),
    bottomReached() {
      const elBottom = this.$el.getBoundingClientRect().bottom;
      return elBottom - this.visibleHeight <= this.distance && !this.loading;
    },
    fulled() {
      return this.$el.getBoundingClientRect().bottom > this.visibleHeight;
    },
    /**
     * 填满父容器
     * @auth:  jsonleex <jsonleex@163.com>
     */
    fillContainer() {
      if (typeof this.onLoadMore === "function") {
        this.loading = true;
        this.onLoadMore();
      }
    },
    /**
     * @param {boolean} [next = true]
     */
    topEnd(next = true) {
      this.requesting = false;
      this.noMore = false;
      next &&
        this.$nextTick(() => {
          if (!this.fulled()) {
            this.fillContainer();
          }
        });
    },
    bottomEnd(noMore) {
      this.loading = false;
      this.noMore = noMore;
      this.$nextTick(() => {
        if (!this.fulled() && !noMore) {
          this.fillContainer();
        }
      });
    },
    // Touch start
    startDrag(e) {
      e = e.changedTouches ? e.changedTouches[0] : e;
      if (
        this.canPulldown &&
        this.scrollTarget.scrollTop <= 0 &&
        !this.loading &&
        !this.requesting
      ) {
        this.$emit("onStart");
        this.startY = e.pageY;
        this.dragging = true;
        this.reset = false;
      }
    },
    // Move
    onDrag(e) {
      const $e = e.changedTouches ? e.changedTouches[0] : e;
      if (this.dragging && $e.pageY - this.startY > 0 && window.scrollY <= 0) {
        // 阻止 原生滚动 事件
        e.preventDefault();
        this.dY = $e.pageY - this.startY;
        this.requesting && (this.dY += this.topDistance);
        this.$emit("onPull", this.dY);
      }
    },
    // Touch end
    stopDrag() {
      this.dragging = false;
      this.$emit("onEnd");
      this.dY > this.topDistance && window.scrollY <= 0
        ? this.beforeRefresh()
        : (this.dY = 0);
    },
    /**
     * 判断是否 满足刷新条件
     *
     * @auth:  jsonleex <jsonleex@163.com>
     */
    beforeRefresh() {
      if (this.requesting) return;
      if (typeof this.onRefresh === "function") {
        this.requesting = true;
        this.dY = Math.tan(this.topBarHeight / 80) * 200;
        this.onRefresh();
      } else {
        this.dY = 0;
      }
    },
    bindEvent() {
      if (typeof this.onLoadMore === "function") {
        this.scEl.addEventListener("scroll", this.handleScrolling);
      }
    },
    autoLoad() {
      if (this.canPulldown && !this.fulled()) {
        this.beforeRefresh();
      }
    }
  }
};
</script>

<style lang="less" scoped>
.load-more-tips {
  height: 40px; /*no*/
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #7c7c7c;
  font-size: 14 * 2px;
}
.load-more-tips > span {
  vertical-align: middle;
  margin-left: 10 * 2px;
}
.load-more-tips i {
  font-style: normal;
  vertical-align: middle;
  transition: all 0.2s ease;
}
.load-more-tips i.up {
  transform: rotate(180deg);
}

.load-more-loading {
  position: relative;
  display: table;
  width: 22 * 2px;
  height: 22 * 2px;
  overflow: hidden;
}
.load-more-loading span {
  animation: loading-fade-dark 1.1s infinite linear;
  position: absolute;
  left: 50%;
  bottom: 2 * 2px;
  margin-left: -0.5 * 2px;
  width: 1 * 2px;
  height: 4 * 2px;
  border-radius: 1 * 2px;
  transform-origin: center -4 * 2px;
}
.load-more-loading span:nth-child(1) {
  animation-delay: 0s;
  transform: rotate(0);
}
.load-more-loading span:nth-child(2) {
  animation-delay: 0.1s;
  transform: rotate(30deg);
}
.load-more-loading span:nth-child(3) {
  animation-delay: 0.2s;
  transform: rotate(60deg);
}
.load-more-loading span:nth-child(4) {
  animation-delay: 0.3s;
  transform: rotate(90deg);
}
.load-more-loading span:nth-child(5) {
  animation-delay: 0.4s;
  transform: rotate(120deg);
}
.load-more-loading span:nth-child(6) {
  animation-delay: 0.5s;
  transform: rotate(150deg);
}
.load-more-loading span:nth-child(7) {
  animation-delay: 0.6s;
  transform: rotate(180deg);
}
.load-more-loading span:nth-child(8) {
  animation-delay: 0.7s;
  transform: rotate(210deg);
}
.load-more-loading span:nth-child(9) {
  animation-delay: 0.8s;
  transform: rotate(240deg);
}
.load-more-loading span:nth-child(10) {
  animation-delay: 0.9s;
  transform: rotate(270deg);
}
.load-more-loading span:nth-child(11) {
  animation-delay: 1s;
  transform: rotate(300deg);
}
.load-more-loading span:nth-child(12) {
  animation-delay: 1.1s;
  transform: rotate(330deg);
}

@keyframes loading-fade-dark {
  0% {
    background-color: #5c5c5c;
  }
  100% {
    background-color: rgba(255, 255, 255, 0);
  }
}
</style>
