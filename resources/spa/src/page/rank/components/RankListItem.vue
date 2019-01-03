<template>
  <div class="rank-list-item">
    <span :class="{ top: index < 3 }" class="rank">{{ index + 1 }}</span>

    <div class="rank-info" @click="$router.push(`/users/${user.id}`)">
      <Avatar :user="user" class="rank-avatar" />
      <div class="rank-title m-text-cut">
        <h6>{{ user.name }}</h6>
        <!-- 用于显示各排行榜数据的插槽 -->
        <slot />
      </div>
    </div>

    <button
      v-if="!isMine"
      :class="{active: isFollow === 'unFollow'}"
      class="follow-btn"
      @click.stop="followUser"
    >
      {{ followText }}
    </button>
  </div>
</template>

<script>
import { followUserByStatus } from '@/api/user.js'

export default {
  name: 'RankListItem',
  props: {
    user: { type: Object, required: true },
    index: { type: Number, required: true },
  },
  computed: {
    isMine () {
      return this.$store.state.CURRENTUSER.id === this.user.id
    },
    isFollow () {
      const { follower = false, following = false } = this.user
      return follower && following
        ? 'eachFollow'
        : follower
          ? 'follow'
          : 'unFollow'
    },
    followText () {
      if (this.isFollow === 'eachFollow') return this.$t('follow.each')
      return this.isFollow === 'follow' ? this.$t('follow.already') : `+ ${this.$t('follow.name')}`
    },
  },
  methods: {
    followUser () {
      if (this.loading) return
      this.loading = true
      followUserByStatus({ status: this.isFollow, id: this.user.id })
        .then(state => {
          this.user.follower = state
          this.loading = false
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>

<style lang="less" scoped>
.rank-list-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 135px;
  padding: 0 30px;
  background: #fff;
  border-bottom: 1px solid #ededed; /* no */

  .rank {
    flex: none;
    width: 2em;
    font-size: 34px;
    color: #999;

    &.top {
      color: @primary;
    }
  }

  .rank-info {
    flex: auto;
    display: flex;
    justify-content: flex-start;
    align-items: center;

    .rank-avatar {
      margin: 0;
      margin-right: 24px;
    }

    .rank-title {
      font-size: 28px;

      p {
        font-size: 24px;
        color: #888;
      }
    }
  }

  .follow-btn {
    flex: none;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 6em;
    height: 1.8em;
    background: #fff;
    color: @primary;
    border: 1px solid currentColor; /* no */
    border-radius: 8px;
    white-space: nowrap;

    &.active {
      color: #fff;
      background-color: @primary;
    }
  }
}
</style>
