<template>
  <footer class="foot-guide">
    <section
      :class="{active: isCurPath('/feed')}"
      class="guide-item"
      @click="to('/feeds?type=hot')">
      <svg class="m-style-svg m-svg-def">
        <use xlink:href="#icon-foot-home"/>
      </svg>
      <span>动态</span>
    </section>
    <section
      :class="{active: isCurPath('/discover')}"
      class="guide-item"
      @click="to('/discover')">
      <svg class="m-style-svg m-svg-def">
        <use xlink:href="#icon-foot-discover"/>
      </svg>
      <span>发现</span>
    </section>
    <section
      class="guide-item plus"
      @click="showPostMenu">
      <svg class="m-style-svg m-svg-def plus">
        <use xlink:href="#icon-plus"/>
      </svg>
    </section>
    <section
      :class="{active: isCurPath('/message')}"
      class="guide-item"
      @click="to('/message/info')">
      <v-badge :dot="hasMsg">
        <svg class="m-style-svg m-svg-def">
          <use xlink:href="#icon-foot-message"/>
        </svg>
      </v-badge>
      <span>消息</span>
    </section>
    <section
      :class="{active: isCurPath('profile')}"
      class="guide-item"
      @click="to('/profile')">
      <v-badge :dot="profile">
        <svg class="m-style-svg m-svg-def">
          <use xlink:href="#icon-foot-profile"/>
        </svg>
      </v-badge>
      <span>我</span>
    </section>
  </footer>
</template>

<script>
import { mapState, mapGetters } from "vuex";

export default {
  name: "FootGuide",
  data() {
    return {
      has_fans: false
    };
  },
  computed: {
    ...mapState({
      has_msg: state =>
        state.MESSAGE.NEW_UNREAD_COUNT.commented +
          state.MESSAGE.NEW_UNREAD_COUNT["feed-comment-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT["group-join-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT.liked +
          state.MESSAGE.NEW_UNREAD_COUNT["news-comment-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT["post-comment-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT["post-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT.system >
        0,
      profile: state =>
        state.MESSAGE.NEW_UNREAD_COUNT.following +
          state.MESSAGE.NEW_UNREAD_COUNT.mutual >
        0
    }),
    ...mapGetters(["hasUnreadChat"]),
    hasMsg() {
      return this.has_msg || this.hasUnreadChat > 0;
    }
  },
  mounted() {
    this.$el.parentNode.style.paddingBottom = "1rem";
  },
  methods: {
    to(path) {
      this.$router.push({ path });
    },
    isCurPath(path) {
      return this.$route.fullPath.indexOf(path) > -1;
    },
    showPostMenu() {
      this.$bus.$emit("post-menu");
    }
  }
};
</script>

<style lang="less" scoped>
.foot-guide {
  background-color: #363844;
  position: fixed;
  z-index: 100;
  left: 0;
  right: 0;
  bottom: 0;
  padding: 0 10px;
  width: 100%;
  max-width: 768px;
  margin: 0 auto;
  height: 100px;
  display: flex;
  box-shadow: 0 -0.026667rem 0.053333rem rgba(0, 0, 0, 0.1);

  .m-svg-def {
    width: 45px;
    height: 45px;
    margin-bottom: 5px;

    &.plus {
      width: 65px;
      height: 65px;
    }
    + .v-badge-dot {
      top: 0;
      box-shadow: 0 0 0 1px #ed3f14; /*no*/
    }
  }

  .guide-item {
    flex: 1;
    display: flex;
    text-align: center;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #ccc;
    .v-badge-dot {
      top: 0;
    }
    &.plus {
      color: #fff !important;
      background-color: @primary;
      margin: 0 15px;
    }
    &.active {
      color: @primary;

      > svg {
        color: @primary;
      }
    }
    span {
      font-size: 24px;
      color: inherit;
    }
  }
}
</style>
