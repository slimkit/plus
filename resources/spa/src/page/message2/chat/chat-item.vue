<template>
  <section class="m-box m-aln-center m-justify-bet chat-item m-main" @click="handelView">
    <div :class="avatarStyle" class="m-flex-shrink0 m-flex-grow0 m-avatar-box m-avatar-box-def">
      <img v-if="avatar" :src="avatar">
    </div>
    <div class="m-box-model m-flex-grow1 m-flex-shrink1 chat-item-main">
      <h2 class="m-text-cut">
        <span class="m-text-cut" style="display: inline-block; max-width: 70%;vertical-align: middle;">{{ name }}</span>
        <span>{{ userCount }}</span>
      </h2>
      <p class="m-text-cut">{{ latest.data }}</p>
    </div>
    <div class="m-box-model m-flex-grow0 m-flex-shrink0 m-justify-center chat-item-ext">
      <span>{{ time + timeOffset | time2tips }}</span>
      <div class="m-box m-aln-center m-justify-end chat-item-count-wrap">
        <span v-show="count > 0" class="chat-item-count">
          <i>{{ count }}</i>
        </span>
      </div>
    </div>
  </section>
</template>

<script>
import { timeOffset } from "@/filters.js";

export default {
  name: "ChatItem",
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      timeOffset
    };
  },
  computed: {
    type() {
      return this.item.type;
    },
    info() {
      return this.item.info;
    },
    name() {
      return this.item.name;
    },
    avatar() {
      const avatar = this.item.avatar || {};
      return avatar.url || null;
    },
    time() {
      return this.item.time;
    },
    latest() {
      return this.item.latest || { data: "" };
    },
    count() {
      return this.item.unreadCount || 0;
    },
    avatarStyle() {
      return this.avatar
        ? ""
        : this.type === "chat"
          ? `m-avatar-box-${this.info.sex}`
          : `m-avatar-box-group`;
    },
    userCount() {
      const { affiliations_count: count } = this.info;
      return count > 0 ? `(${count})` : "";
    }
  },
  methods: {
    handelView() {
      // this.count > 0 &&
      //   // WebIMDB.readMessage(this.item.type, this.item.from).then(res => {
      //   //   res > 0 && this.$store.dispatch("initChats");
      //   // });
      this.$nextTick(() => {
        this.$router.push(`/chats/${this.item.id}`);
      });
    }
  }
};
</script>

<style lang="less" scoped>
.chat-item {
  padding: 30px 20px;
  height: 135px;
  border-bottom: 1px solid @border-color; /* no */

  &-main {
    overflow: hidden;
    margin-left: 30px;
    margin-right: 30px;
    font-size: 28px;
    color: @text-color3;
    h2 {
      font-size: 32px;
      color: @text-color1;
    }
  }
  &-ext {
    align-self: flex-start;
    font-size: 24px;
    color: #ccc;
  }

  &-count {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    height: 32px;
    min-width: 32px;
    color: #fff;
    border-radius: 16px;
    background-color: @error;
    i {
      transform: scale(0.9);
    }
  }
}
</style>
