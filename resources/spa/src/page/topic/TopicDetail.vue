<template>
  <div class="p-topic-detail" :class="{cover}">
    <PortalPanel
      ref="portal"
      :title="topic.name"
      :cover="cover"
      :loading="loading"
      :no-data="!feeds.length"
      @more="onMoreClick"
      @loadmore="fetchFeeds(true)"
      @update="fetchTopic"
    >
      <div slot="head" class="banner-content">
        <div class="detail">
          <div class="info">
            <h1>{{ topic.name }}</h1>
            <p v-show="creator.name">{{ creator.name | t('feed.topic.creator') }}</p>
          </div>
        </div>
      </div>

      <template slot="info">
        <p v-if="topic.desc" class="description">
          {{ topic.desc }}
        </p>
        <div v-if="followers.length" class="participants">
          <div class="title" @click="gotoParticipants">
            <strong>{{ $t('feed.topic.participants') }}</strong>
            <svg v-if="followers.length >= 4" class="m-style-svg m-svg-small">
              <use xlink:href="#icon-arrow-right" />
            </svg>
          </div>
          <ul class="user-list">
            <li
              v-for="user in followers"
              :key="user.id"
              class="user-item"
            >
              <Avatar class="avatar" :user="user" />
              <span class="user-name m-text-cut">{{ user.name }}</span>
            </li>
          </ul>
        </div>
      </template>

      <div slot="sticky" class="sticky-bar">
        <div class="info">
          <span>{{ topic.feeds_count | t('feed.count') }}</span>
          <span>{{ topic.followers_count | t('follow.count') }}</span>
        </div>
        <div v-if="!isMine" class="follow-btn">
          <button v-if="topic.has_followed" @click="unfollowTopic">{{ $t('follow.already') }}</button>
          <button
            v-else
            class="unfollow"
            @click="followTopic"
          >
            + {{ $t('follow.name') }}
          </button>
        </div>
      </div>

      <template slot="main">
        <ul class="user-feeds">
          <li
            v-for="feed in feeds"
            v-if="feed.id"
            :key="`feed${feed.id}`"
            class="feed-item"
          >
            <FeedCard :feed="feed" :current-topic="topic.id" />
          </li>
        </ul>
      </template>
    </PortalPanel>

    <svg class="m-style-svt m-svg-huge post-btn" @click="$refs.postMenu.open()">
      <use xlink:href="#icon-topic-edit" />
    </svg>

    <TopicPostMenu ref="postMenu" :topic="topic" />
  </div>
</template>

<script>
import { limit } from '@/api'
import * as api from '@/api/topic'
import * as userApi from '@/api/user'
import PortalPanel from '@/components/PortalPanel'
import FeedCard from '@/components/FeedCard/FeedCard'
import TopicPostMenu from './components/TopicPostMenu.vue'

export default {
  name: 'TopicDetail',
  components: {
    PortalPanel,
    FeedCard,
    TopicPostMenu,
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
    isMine () {
      return this.creator.id === this.currentUser.id
    },
    followers () {
      return [this.creator, ...this.participants]
    },
  },
  watch: {
    $route (to, from) {
      this.fetchTopic(true)
    },
  },
  created () {
    this.fetchTopic(true)
  },
  methods: {
    fetchTopic (init = false) {
      if (init) this.loading = true
      api.getTopicDetail(this.topicId)
        .then(({ data }) => {
          this.loading = false
          this.$refs.portal.afterUpdate()
          this.topic = data
          this.preTopicId = this.topic.id
          this.fetchCreator()
          this.fetchParticipants()
          this.fetchFeeds()
        })
    },
    async fetchCreator () {
      this.creator = await userApi.getUserInfoById(this.topic.creator_user_id)
    },
    async fetchParticipants () {
      const users = this.topic.participants || []
      if (!users.length) return (this.participants = [])
      const params = { id: users.join(','), limit: 4 }
      const data = await this.$store.dispatch('user/getUserList', params)
      this.participants = data
    },
    fetchFeeds (loadmore) {
      const params = {}
      const lastFeed = [...this.feeds].pop() || {}
      if (loadmore) params.index = lastFeed.index
      this.$refs.portal.beforeLoadMore()
      api.getTopicFeeds(this.topicId, params)
        .then(({ data }) => {
          this.fetching = false
          this.$refs.portal.afterLoadMore(data.length < limit)
          if (loadmore) this.feeds.push(...data)
          else this.feeds = data
        })
    },
    async followTopic () {
      await api.followTopic(this.topicId)
      this.$Message.success(this.$t('feed.topic.follow.success'))
      this.fetchTopic()
    },
    async unfollowTopic () {
      await api.unfollowTopic(this.topicId)
      this.$Message.success(this.$t('feed.topic.follow.cancel'))
      this.fetchTopic()
    },
    onMoreClick () {
      const actions = []
      if (this.isMine) {
        actions.push({
          text: this.$t('edit'),
          method: () => {
            this.$router.push({ name: 'TopicEdit', params: { topicId: this.topicId, topicName: this.topic.name } })
          },
        })
      } else {
        actions.push({
          text: this.$t('report.name'),
          method: () => {
            this.$bus.$emit('report', {
              type: 'topic',
              payload: this.topicId,
              username: this.creator.name,
              reference: this.topic.name,
            })
          },
        })
      }
      this.$bus.$emit('actionSheet', actions)
    },
    gotoParticipants () {
      if (this.followers.length < 4) return
      this.$router.push({ name: 'TopicParticipants', params: { topicId: this.topicId } })
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

    .title {
      display: flex;
      justify-content: space-between;
      align-items: center;

      .m-style-svg {
        color: @text-color3;
      }
    }

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
          max-width: 6em;
          margin-top: 20px;
          font-size: 24px;
        }
      }
    }
  }

  .sticky-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 30px;
    height: 70px;

    .info {
      span + span {
        margin-left: 1em;
      }
    }

    .follow-btn {
      button {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 6em;
        border: 1px solid @primary;/*no*/
        border-radius: 8px;
        background-color: transparent;
        color: @primary;
        font-size: 22px;

        &.unfollow {
          background-color: @primary;
          color: #fff;
        }
      }
    }
  }

  .user-feeds {
    .feed-item {
      margin-bottom: 20px;
    }
  }

  .post-btn {
    position: fixed;
    bottom: 45px;
    right: 45px;
  }

  &.cover {
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
