<template>
  <div>
    <RankUsersComponent
      v-for="rank in ranks"
      :key="rank.api"
      v-bind="rank"
    />
  </div>
</template>

<script>
import RankUsersComponent from '../components/RankUsersComponent'
import i18n from '@/i18n'

const ranks = [
  {
    title: i18n.t('rank.feed', [i18n.t('date.today')]),
    listUrl: '/rank/f/today',
    api: '/feeds/ranks?type=day&limit=15',
    name: 'rankFeedsToday',
  },
  {
    title: i18n.t('rank.feed', [i18n.t('date.week')]),
    listUrl: '/rank/f/week',
    api: '/feeds/ranks?type=week&limit=15',
    name: 'rankFeedsWeek',
  },
  {
    title: i18n.t('rank.feed', [i18n.t('date.month')]),
    listUrl: '/rank/f/month',
    api: '/feeds/ranks?type=month&limit=15',
    name: 'rankFeedsMonth',
  },
]

export default {
  name: 'RankFeeds',
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
