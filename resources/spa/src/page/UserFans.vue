<template>
  <div class="p-user-fans">

    <nav class="m-box m-head-top m-lim-width m-pos-f m-main m-bb1" style="padding: 0 10px;">
      <div class="m-box m-aln-center m-flex-shrink0 ">
        <svg class="m-style-svg m-svg-def" @click="goBack">
          <use xlink:href="#icon-back"/>
        </svg>
      </div>
      <ul class="m-box m-flex-grow1 m-aln-center m-justify-center m-flex-base0 m-head-nav">
        <router-link
          :to="`/users/${userID}/followers`"
          tag="li"
          active-class="active"
          exact
          replace>
          <a>粉丝</a>
        </router-link>
        <router-link
          :to="`/users/${userID}/followings`"
          tag="li"
          active-class="active"
          exact
          replace>
          <a>关注</a>
        </router-link>
      </ul>
      <div class="m-box m-justify-end"/>
    </nav>

    <main style="padding-top: 0.9rem">
      <jo-load-more
        ref="loadmore"
        @onRefresh="onRefresh"
        @onLoadMore="onLoadMore">
        <user-item
          v-for="user in users"
          v-if="user.id"
          :user="user"
          :key="`user-item-${user.id}`"
        />
      </jo-load-more>
    </main>
  </div>
</template>

<script>
import UserItem from "@/components/UserItem";
import { getUserFansByType } from "@/api/user.js";

const typeMap = ["followers", "followings"];
export default {
  name: "UserFans",
  components: {
    UserItem
  },
  data() {
    return {
      followers: [],
      followings: [],
      preUID: 0,
      USERSChangeTracker: 1
    };
  },
  computed: {
    userID() {
      return ~~this.$route.params.userID;
    },
    type() {
      return this.$route.params.type;
    },
    param() {
      return {
        limit: 15,
        type: this.type,
        uid: this.userID
      };
    },
    users: {
      get() {
        return this.type && this.$data[this.type];
      },
      set(val) {
        this.$data[this.type] = val;
      }
    }
  },
  watch: {
    type(val) {
      typeMap.includes(val) && this.$refs.loadmore.beforeRefresh();
    },
    users(val) {
      val &&
        val.length > 0 &&
        val.forEach(user => {
          this.$store.commit("SAVE_USER", user);
        });
    }
  },
  activated() {
    // 判断是否清空上一次的数据
    this.userID === this.preUID ||
      ((this.followers = []), (this.followings = []));

    this.$refs.loadmore.beforeRefresh();
    this.preUID = this.userID;
  },
  methods: {
    onRefresh(callback) {
      getUserFansByType(this.param).then(data => {
        this.users = data;
        callback(data.length < this.param.limit);
      });
    },
    onLoadMore(callback) {
      getUserFansByType({ ...this.param, offset: this.users.length }).then(
        data => {
          this.users = [...this.users, ...data];
          callback(data.length < this.param.limit);
        }
      );
    }
  }
};
</script>
