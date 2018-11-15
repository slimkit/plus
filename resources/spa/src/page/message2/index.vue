<template>
  <div class="p-message">
    <header class="m-box m-head-top m-lim-width m-pos-f m-main m-bb1">
      <ul class="m-box m-flex-grow1 m-aln-center m-justify-center m-flex-base0 m-head-nav">
        <router-link 
          tag="li" 
          to="/message/info" 
          replace 
          exact 
          active-class="active">
          <v-badge :dot="has_msg">
            <a>消息</a>
          </v-badge>
        </router-link>
        <router-link 
          tag="li" 
          to="/message/chats" 
          replace 
          exact 
          active-class="active">
          <v-badge :dot="hasUnreadChat > 0">
            <a>聊天</a>
          </v-badge>
        </router-link>
      </ul>
    </header>
    <main style="padding-top: 0.9rem">
      <router-view/>
    </main>
    <foot-guide/>
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
export default {
  name: "MessageIndex",
  data() {
    return {};
  },
  computed: {
    ...mapState({
      // 新消息提示
      has_msg: state =>
        state.MESSAGE.NEW_UNREAD_COUNT.commented +
          state.MESSAGE.NEW_UNREAD_COUNT["feed-comment-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT["group-join-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT.liked +
          state.MESSAGE.NEW_UNREAD_COUNT["news-comment-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT["post-comment-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT["post-pinned"] +
          state.MESSAGE.NEW_UNREAD_COUNT.system >
        0
    }),
    ...mapGetters(["hasUnreadChat"])
  },

  created() {
    this.$store.dispatch("GET_UNREAD_COUNT");
    this.$store.dispatch("GET_NEW_UNREAD_COUNT");
  }
};
</script>

<style lang="less">
.p-message {
  min-height: 100vh;
  main {
    height: calc(~"100vh - 90px - 100px");
  }
  .m-entry-group {
    padding: 0 20px;
    .m-entry {
      align-items: stretch;
      padding: 30px 0;
      height: initial;
    }
    .m-entry-prepend {
      margin: 0;
      width: 76px;
      height: 76px;
    }
    .m-entry-main {
      margin-left: 30px;
      margin-right: 30px;
      h2 {
        font-weight: 400;
        font-size: 32px;
      }
      p {
        font-size: 24px;
        color: @text-color3;
      }
    }
    .m-entry-end {
      color: #ccc;
      font-size: 24px;
    }
  }
}
</style>
