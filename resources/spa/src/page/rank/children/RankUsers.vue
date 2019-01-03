<template>
  <div>
    <RankUsersComponent
      v-for="rank in ranks"
      :key="rank.name"
      :title="rank.title"
      :api="rank.api"
      :name="rank.name"
      :list-url="rank.listUrl"
    />
  </div>
</template>

<script>
import RankUsersComponent from '../components/RankUsersComponent'
import i18n from '@/i18n'

const ranks = [
  {
    title: i18n.t('rank.fans'),
    listUrl: '/rank/users/followers',
    api: '/ranks/followers?limit=15',
    name: 'rankFollowers',
  },
  {
    title: i18n.t('rank.checkin'),
    listUrl: '/rank/users/checkin',
    api: '/checkin-ranks?limit=15',
    name: 'rankCheckin',
  },
]

export default {
  name: 'RankUsers',
  components: {
    RankUsersComponent,
  },
  data () {
    return {
      ranks,
    }
  },
}
</script>
