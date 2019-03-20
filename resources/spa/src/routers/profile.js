import i18n from '@/i18n'
import ProfileNews from '@/page/profile/children/ProfileNews'
import ProfileCollections from '@/page/profile/ProfileCollection.vue'
import ProfileCollectionNews from '@/page/profile/collection/ProfileCollection.news.vue'
import ProfileCollectionFeeds from '@/page/profile/collection/ProfileCollection.feeds.vue'
import ProfileCollectionAnswers from '@/page/profile/collection/ProfileCollection.answers.vue'
import ProfileCollectionPosts from '@/page/profile/collection/ProfileCollection.posts.vue'
import ProfileCertificate from '@/page/profile/Certificate.vue'
import ProfileCertification from '@/page/profile/Certification.vue'

export default [
  {
    path: '/profile/news/:type(released|auditing|rejected)',
    component: ProfileNews,
    meta: { title: i18n.t('profile.news.name'), keepAlive: true },
  },
  {
    path: '/profile/collection',
    component: ProfileCollections,
    name: 'profileCollection',
    meta: { title: i18n.t('profile.collect.name'), keepAlive: true },
    redirect: '/profile/collection/feeds',
    children: [
      {
        path: 'feeds',
        component: ProfileCollectionFeeds,
        meta: { title: i18n.t('profile.collect.feed') },
      },
      {
        path: 'news',
        component: ProfileCollectionNews,
        meta: { title: i18n.t('profile.collect.news') },
      },
      {
        path: 'answers',
        component: ProfileCollectionAnswers,
        meta: { title: i18n.t('profile.collect.answer') },
      },
      {
        path: 'posts',
        component: ProfileCollectionPosts,
        meta: { title: i18n.t('profile.collect.post') },
      },
    ],
  },
  {
    name: 'ProfileCertificate',
    path: '/profile/certificate',
    component: ProfileCertificate,
    meta: { title: i18n.t('certificate.apply') },
  },
  {
    name: 'ProfileCertification',
    path: '/profile/certification',
    component: ProfileCertification,
    meta: { title: i18n.t('certificate.info') },
  },
]
