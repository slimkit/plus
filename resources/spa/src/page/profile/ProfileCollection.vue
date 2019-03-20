<template>
  <div class="p-profile-collection">
    <CommonHeader>{{ $t('profile.collect.name') }}</CommonHeader>

    <main>
      <NavTab class="nav" :nav="types" />
      <KeepAlive>
        <RouterView />
      </KeepAlive>
    </main>
  </div>
</template>

<script>
import NavTab from '@/components/common/NavTab.vue'
import i18n from '@/i18n'

const types = [
  { name: 'feed', label: i18n.t('feed.name'), route: '/profile/collection/feeds' },
  { name: 'news', label: i18n.t('news.name'), route: '/profile/collection/news' },
  { name: 'answer', label: i18n.t('question.answer.name'), route: '/profile/collection/answers' },
  { name: 'post', label: i18n.t('group.post.name'), route: '/profile/collection/posts' },
]

export default {
  name: 'ProfileCollection',
  components: { NavTab },
  data () {
    return {
      types,
    }
  },
}
</script>
