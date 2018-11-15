<template>
  <div :class="`${prefixCls}`">

    <common-header>系统通知</common-header>

    <jo-load-more
      ref="loadmore"
      :class="`${prefixCls}-loadmore`"
      @onRefresh="onRefresh"
      @onLoadMore="onLoadMore" >
      <section
        v-for="notification in notifications"
        :key="notification.id"
        class="m-box m-aln-st m-main m-bb1 notification-item">
        <h5 class="m-flex-grow1 m-flex-shrink1">{{ notification.data.content }}</h5>
        <p class="m-flex-grow0 m-flex-shrink0">{{ notification.created_at | time2tips }}</p>
      </section>
    </jo-load-more>
  </div>
</template>

<script>
import _ from "lodash";
import { getNotifications } from "@/api/message.js";

const prefixCls = "notification";

export default {
  name: "MyNotifications",
  data() {
    return {
      prefixCls,
      notifications: []
    };
  },
  mounted() {
    this.$refs.loadmore.beforeRefresh();
  },
  methods: {
    /**
     * 下拉刷新
     * @Author   Wayne
     * @DateTime 2018-02-10
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    onRefresh() {
      getNotifications().then(({ data }) => {
        this.$http.put("/user/notifications/all");
        this.$refs.loadmore.afterRefresh(data.length < 15);
        this.notifications = data;
      });
    },
    /**
     * 上拉加载
     * @Author   Wayne
     * @DateTime 2018-02-10
     * @Email    qiaobin@zhiyicx.com
     * @return   {[type]}            [description]
     */
    onLoadMore() {
      const { length: offset = 0 } = this.notifications;
      getNotifications(offset).then(({ data }) => {
        this.$refs.loadmore.afterLoadMore(data.length < 15);
        this.notifications = _.unionBy([...this.notifications, ...data]);
      });
    }
  }
};
</script>

<style lang="less" scoped>
.notification-item {
  padding: 30px;

  h5 {
    color: #333;
    font-size: 30px;
    font-weight: 400;
  }

  p {
    margin-left: 30px;
    color: #999;
    font-size: 24px;
  }
}
</style>
