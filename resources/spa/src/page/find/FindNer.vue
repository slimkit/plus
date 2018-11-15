<template>
  <jo-load-more
    key="find-ner"
    ref="loadmore"
    @onRefresh="onRefresh"
    @onLoadMore="onLoadMore">
    <user-item
      v-for="user in users"
      :user="user"
      :key="user.id"/>
  </jo-load-more>
</template>

<script>
import { mapState } from "vuex";
import UserItem from "@/components/UserItem.vue";
import { findNearbyUser } from "@/api/user.js";

export default {
  name: "FindNer",
  components: {
    UserItem
  },
  data() {
    return {
      users: [],
      page: 1,
      isActive: false
    };
  },
  computed: {
    ...mapState(["POSITION"]),
    lat() {
      return this.POSITION.lat;
    },
    lng() {
      return this.POSITION.lng;
    }
  },
  activated() {
    this.$refs.loadmore.beforeRefresh();
  },
  methods: {
    async formateUsers(users) {
      const userList = [];
      for (let item of users) {
        userList.push(item.user_id);
      }
      const data = await this.$store.dispatch("user/getUserList", {
        id: userList.join(",")
      });
      // 修正数据顺序
      const sortedUsers = [];
      for (const user_id of userList) {
        const user = data.find(u => u.id === user_id);
        user && sortedUsers.push(user);
      }
      this.users = sortedUsers;
    },
    onRefresh(callback) {
      this.page = 1;
      findNearbyUser({ lat: this.lat, lng: this.lng }, this.page).then(
        ({ data = [] }) => {
          this.users = [];
          this.formateUsers(data);
          this.page = 2;
          callback(data.length < 15);
        }
      );
    },
    onLoadMore(callback) {
      findNearbyUser({ lat: this.lat, lng: this.lng }, this.page).then(
        ({ data = [] }) => {
          this.page += 1;
          this.formateUsers(data);
          callback(data.length < 15);
        }
      );
    }
  }
};
</script>
