<template>
  <jo-load-more
    key="find-rec"
    ref="loadmore"
    @onRefresh="onRefresh"
    @onLoadMore="onLoadMore">
    <user-item
      v-for="user in users"
      :user="user"
      :key="`${user.id}-from-${user.searchFrom}`"/>
  </jo-load-more>
</template>

<script>
import UserItem from "@/components/UserItem.vue";
import * as userApi from "@/api/user";

export default {
  name: "FindRec",
  components: { UserItem },
  data() {
    return {
      users: []
    };
  },
  activated() {
    this.$refs.loadmore.beforeRefresh();
  },
  methods: {
    onRefresh() {
      const recommendPromise = userApi
        .findUserByType("recommends")
        .then(({ data: users }) => {
          this.users = users;
          return users.map(u => {
            u.searchFrom = "recommend";
            return u;
          });
        });
      const tagsPromise = userApi
        .findUserByType("find-by-tags")
        .then(({ data: users }) => {
          this.users = users;
          this.$refs.loadmore.afterRefresh(users.length < 15);
          return users.map(u => {
            u.searchFrom = "tags";
            return u;
          });
        });
      // 并发获取用户
      Promise.all([recommendPromise, tagsPromise])
        .then(([recommendUsers, tagsUsers]) => {
          this.users = [...recommendUsers, ...tagsUsers];
        })
        .catch(err => {
          this.$refs.loadmore.afterRefresh(false);
          return err;
        });
    },
    async onLoadMore() {
      const { data: users } = await userApi.findUserByType("find-by-tags", {
        offset: this.users.length
      });
      this.users = [...this.users, ...users];
      this.$refs.loadmore.afterLoadMore(users.length < 15);
    }
  }
};
</script>
