<template>
  <div class="p-topic-participants">
    <CommonHeader>{{ $t('feed.topic.participants') }}</CommonHeader>

    <main>
      <JoLoadMore ref="loadmore" @onRefresh="onRefresh">
        <UserItem
          v-for="user in participants"
          :key="user.id"
          :user="user"
        />
      </JoLoadMore>
    </main>
  </div>
</template>

<script>
import { limit } from '@/api'
import * as api from '@/api/topic'
import UserItem from '@/components/UserItem.vue'

export default {
  name: 'TopicParticipants',
  components: { UserItem },
  data () {
    return {
      creator: null,
      participants: [],
    }
  },
  computed: {
    topicId () {
      return this.$route.params.topicId
    },
  },
  mounted () {
    this.$refs.loadmore.beforeRefresh()
  },
  methods: {
    async onRefresh () {
      const { data: participants } = await api.getTopicParticipants(this.topicId)
      const users = await this.fetchUser(participants)
      this.$refs.loadmore.afterRefresh(users.length < limit)
      this.participants = users
    },
    async onLoadMore () {
      const offset = this.participants.length
      const { data: participants } = await api.getTopicParticipants(this.topicId, { offset })
      const users = await this.fetchUser(participants)
      this.$refs.loadmore.afterLoadMore(users.length < limit)
      this.participants.push(...users)
    },
    async fetchUser (ids) {
      return this.$store.dispatch('user/getUserList', { id: ids.join(',') })
    },
  },
}
</script>
