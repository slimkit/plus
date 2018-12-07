<template>
  <div class="p-topic-detail" :class="{cover}">
    <PortalPanel
      ref="portal"
      :title="topic.name"
      :cover="cover"
      :loading="loading"
      @loadmore="fetchFeeds(true)"
    >
      <div slot="head" class="banner-content">
        <div class="detail">
          <div class="info">
            <h1>{{ topic.name }}</h1>
            <p v-show="creator.name">创建者：{{ creator.name }}</p>
          </div>
          <div class="follow-btn">
            <button v-if="topic.has_followed" @click="unfollowTopic">已关注</button>
            <button v-else @click="followTopic">+ 关注</button>
          </div>
        </div>
      </div>

      <template slot="info">
        <p v-if="topic.desc" class="description">
          {{ topic.desc }}
        </p>
        <div v-if="participants.length" class="participants">
          <div>
            <strong>参与话题的人</strong>
            <svg class="m-style-svg m-svg-def">
              <use xlink:href="#icon-arrow-r" />
            </svg>
          </div>
          <ul class="user-list">
            <li
              v-for="user in participants"
              :key="user.id"
              class="user-item"
            >
              <Avatar class="avatar" :user="user" />
              <span class="user-name">{{ user.name }}</span>
            </li>
          </ul>
        </div>
      </template>

      <div slot="sticky" class="sticky-bar">
        <span>{{ topic.feeds_count }} 条动态</span>
        <span>{{ topic.followers_count }} 人关注</span>
      </div>

      <template slot="main">
        <ul class="user-feeds">
          <li
            v-for="feed in feeds"
            v-if="feed.id"
            :key="`feed${feed.id}`"
          >
            <FeedCard :feed="feed" />
          </li>
        </ul>
      </template>
    </PortalPanel>
  </div>
</template>

<script>
import * as api from '@/api/topic'
import * as userApi from '@/api/user'
import PortalPanel from '@/components/PortalPanel'
import FeedCard from '@/components/FeedCard/FeedCard'

export default {
  name: 'TopicDetail',
  components: {
    PortalPanel,
    FeedCard,
  },
  data () {
    return {
      topic: { },
      creator: {},
      participants: [],
      feeds: [],

      loading: true,
      preTopicId: 0,
    }
  },
  computed: {
    topicId () {
      return this.$route.params.topicId
    },
    cover () {
      const { logo = {} } = this.topic
      return logo.url || false
    },
  },
  created () {
    this.fetchTopic()
  },
  activated () {
    if (this.topicId !== this.preTopicId) this.fetchTopic()
  },
  methods: {
    fetchTopic () {
      this.loading = true
      api.getTopicDetail(this.topicId)
        .then(({ data }) => {
          this.loading = false
          this.topic = data
          this.preTopicId = this.topic.id
          this.fetchCreator()
          this.fetchParticipants()
          this.fetchFeeds()
        })
    },
    fetchCreator () {
      this.creator = userApi.getUserInfoById(this.topic.creator_user_id)
    },
    fetchParticipants () {
      const users = this.topic.participants || []
      if (!users.length) return
      userApi.getUserList({ id: users.join(','), limit: 4 })
        .then(({ data }) => {
          this.participants = data
        })
    },
    fetchFeeds (loadmore) {
      const params = {}
      const lastFeed = [...this.feeds].pop() || {}
      if (loadmore) params.index = lastFeed.index
      this.$refs.portal.beforeLoadMore()
      api.getTopicFeeds(this.topicId, params)
        .then(({ data }) => {
          this.fetching = false
          this.$refs.portal.afterLoadMore(data.length < 15)
          if (loadmore) this.feeds.push(...data)
          else this.feeds = data
        })
    },
    async followTopic () {
      await api.followTopic(this.topicId)
      this.topic.has_followed = true
      this.$Message.success('关注话题成功')
    },
    async unfollowTopic () {
      await api.unfollowTopic(this.topicId)
      this.topic.has_followed = false
      this.$Message.success('取消关注成功')
    },
  },
}
</script>

<style lang="less" scoped>
.p-topic-detail {
  .banner-content {
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    top: 0;
    display: flex;
    align-items: flex-end;
    padding: 0 30px;

    .detail {
      display: flex;
      justify-content: space-between;
      width: 100%;
      height: 120px;
    }

    .info {
      font-size: 24px;
      > h1 {
        font-size: 140%;
        margin-bottom: 10px;
      }
    }

    .follow-btn button {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 5em;
      height: 50px;
      border: 1px solid @primary;
      border-radius: 8px;
      background-color: transparent;
      color: @primary;
      font-size: 26px;
    }
  }

  .description {
    background-color: #fff;
    padding: 30px;
    color: @text-color3;
    font-size: 28px;
    letter-spacing: 1px;/*no*/
  }

  .participants {
    padding: 30px;
    font-size: 26px;
    background-color: #fff;
    border-top: 1px solid @border-color;

    .user-list {
      display: flex;
      margin-top: 30px;

      .user-item {
        flex: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 25%;

        .avatar {
          width: 110px;
          height: 110px;
        }

        .user-name {
          margin-top: 20px;
          font-size: 24px;
        }
      }
    }
  }

  .sticky-bar {
    padding: 20px 30px;

    span + span {
      margin-left: 1em;
    }
  }

  &.cover {
    .follow-btn button {
      border: 1px solid #fff;
      color: #fff;
      box-shadow: 0 -1px rgba(0,0,0, 0.35); /*no*/
    }

    .banner-content::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
      z-index: -1;
      opacity: 0.2;
      background-color: #000;
    }
  }
}
</style>
