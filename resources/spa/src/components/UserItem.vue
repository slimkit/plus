<template>
  <div class="c-user-item" @click="toUserHome">
    <avatar :user="user" />
    <section class="user-item-body m-text-cut">
      <h2 class="m-text-box m-text-cut">{{ user.name }}</h2>
      <p class="m-text-box m-text-cut">{{ user.bio || "这家伙很懒，什么也没留下" }}</p>
    </section>
    <button
      v-if="!isMine"
      :class="{active: isFollow === 'unFollow'}"
      class="follow-btn"
      @click.stop="followUser">
      {{ followText }}
    </button>
  </div>
</template>
<script>
import { followUserByStatus } from "@/api/user.js";

export default {
  name: "UserItem",
  props: {
    user: { type: Object, required: true },
    link: { type: Boolean, default: true }
  },
  data() {
    return {
      loading: false
    };
  },
  computed: {
    follower: {
      get() {
        return this.user.follower;
      },
      set(val) {
        this.user.follower = val;
      }
    },
    isFollow: {
      get() {
        const following = this.user.following;
        return this.follower && following
          ? "eachFollow"
          : this.follower
            ? "follow"
            : "unFollow";
      },
      set(val) {
        this.follower = val;
      }
    },
    followText() {
      if (this.isFollow == "eachFollow") return "相互关注";
      return this.isFollow === "follow" ? "已关注" : "+ 关注";
    },
    isMine() {
      return this.$store.state.CURRENTUSER.id === this.user.id;
    }
  },
  created() {
    this.$store.commit("SAVE_USER", this.user);
  },
  methods: {
    toUserHome() {
      this.link && this.$router.push(`/users/${this.user.id}`);
    },
    followUser() {
      if (this.loading) return;
      this.loading = true;
      followUserByStatus({
        id: this.user.id,
        status: this.isFollow
      }).then(follower => {
        this.user.follower = follower;
        this.loading = false;
      });
    }
  }
};
</script>

<style lang='less' scoped>
.c-user-item {
  display: flex;
  align-items: center;
  padding: 30px 20px;
  background-color: #fff;

  & + & {
    border-top: 1px solid #ededed;
  }

  .user-item-body {
    display: flex;
    flex-direction: column;
    flex: auto;
    margin-left: 30px;
    margin-right: 30px;

    h2 {
      margin: 9px 0;
      font-size: 32px;
    }
    p {
      margin: 9px 0;
      font-size: 28px;
      color: @text-color3;
    }
  }

  .follow-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    flex: none;
    width: 5em;
    height: 1.8em;
    background: #fff;
    color: @primary;
    border: 1px solid currentColor;
    border-radius: 8px;
    white-space: nowrap;

    &.active {
      color: #fff;
      background-color: @primary;
    }
  }
}
</style>
