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

const types = [
  { name: 'feed', label: '动态', route: '/profile/collection/feeds' },
  { name: 'news', label: '资讯', route: '/profile/collection/news' },
  { name: 'answer', label: '回答', route: '/profile/collection/answers' },
  { name: 'post', label: '帖子', route: '/profile/collection/posts' },
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
