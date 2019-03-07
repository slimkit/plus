<template>
  <div class="p-message-home">
    <JoLoadMore
      ref="loadmore"
      :show-bottom="false"
      :auto-load="true"
      @onRefresh="fetchMessage"
    >
      <ul class="message-list">
        <RouterLink to="/message/system" tag="li">
          <svg class="m-style-svg m-svg-large"><use xlink:href="#icon-message-notice" /></svg>
          <div class="info">
            <h2>系统消息 <span class="time">{{ system.first.created_at | time2tips }}</span></h2>
            <p v-if="system.badge" class="description">
              <template v-if="system.first.data.contents">{{ system.first.data.contents }}</template>
              <template v-else>{{ system.first.data | getNotificationDisplay }}</template>

              <BadgeIcon v-if="system.badge" :count="system.badge" />
            </p>
            <p v-else class="description">暂无系统消息</p>
          </div>
        </RouterLink>

        <RouterLink to="/message/comments" tag="li">
          <svg class="m-style-svg m-svg-large"><use xlink:href="#icon-message-comment" /></svg>
          <div class="info">
            <h2>收到的评论 <span class="time">{{ comment.last_created_at | time2tips }}</span></h2>
            <p v-if="comment.badge" class="description">{{ (comment.preview_users_names || []).join('、') }}评论了我 <BadgeIcon v-if="comment.badge" :count="comment.badge" /></p>
            <p v-else class="description">还没有人评论了我</p>
          </div>
        </RouterLink>

        <RouterLink to="/message/likes" tag="li">
          <svg class="m-style-svg m-svg-large"><use xlink:href="#icon-message-like" /></svg>
          <div class="info">
            <h2>收到的赞 <span class="time">{{ like.last_created_at | time2tips }}</span></h2>
            <p v-if="like.badge" class="description">{{ (like.preview_users_names || []).join('、') }}赞了我 <BadgeIcon v-if="like.badge" :count="like.badge" /></p>
            <p v-else class="description">还没有人赞了我</p>
          </div>
        </RouterLink>

        <RouterLink to="/message/audits" tag="li">
          <svg class="m-style-svg m-svg-large"><use xlink:href="#icon-message-audit" /></svg>
          <div class="info">
            <h2>审核通知</h2>
            <p class="description">你有未审核的信息请及时处理 <BadgeIcon v-if="unreadAudits" :count="unreadAudits" /></p>
          </div>
        </RouterLink>
      </ul>
    </JoLoadMore>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
  name: 'MessageHome',
  data () {
    return {
      audit: {},
    }
  },
  computed: {
    ...mapState('message', {
      at: 'at',
      comment: 'comment',
      follow: 'follow',
      like: 'like',
      system: 'system',
    }),
    ...mapGetters('message', {
      unreadAudits: 'unreadAudits',
    }),
  },

  methods: {
    ...mapActions('message', {
      getNotificationStatistics: 'getNotificationStatistics',
      getGroupJoinedList: 'getGroupJoinedList',
    }),
    async fetchMessage () {
      await this.getNotificationStatistics()
      this.getGroupJoinedList()
      this.$refs.loadmore.afterRefresh()
    },
  },
}
</script>

<style lang="less" scoped>
.p-message-home {
  .message-list {
    background-color: #fff;
    font-size: 28px;

    > li {
      display: flex;
      align-items: center;
      padding: 15px;
      border-bottom: 1px solid #ededed;

      .m-svg-large {
        flex: none;
        width: 90px;
        height: 90px;
        margin-right: 15px;
      }
    }

    .info {
      flex: auto;
      display: flex;
      flex-direction: column;
      justify-content: space-between;

      h2 {
        display: flex;
        justify-content: space-between;
      }

      p {
        display: flex;
        justify-content: space-between;
      }

      .time, .description {
        color: #999;
      }
    }
  }
}
</style>
