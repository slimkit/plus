import ProfileNews from "@/page/profile/children/ProfileNews";
import ProfileCollections from "@/page/profile/ProfileCollection.vue";
import ProfileCollectionNews from "@/page/profile/collection/ProfileCollection.news.vue";
import ProfileCollectionFeeds from "@/page/profile/collection/ProfileCollection.feeds.vue";
import ProfileCollectionAnswers from "@/page/profile/collection/ProfileCollection.answers.vue";
import ProfileCollectionPosts from "@/page/profile/collection/ProfileCollection.posts.vue";
import ProfileCertificate from "@/page/profile/Certificate.vue";
import ProfileCertification from "@/page/profile/Certification.vue";

export default [
  {
    path: "/profile/news/:type(released|auditing|rejected)",
    component: ProfileNews,
    meta: { title: "我的投稿", keepAlive: true }
  },
  {
    path: "/profile/collection",
    component: ProfileCollections,
    name: "profileCollection",
    meta: { title: "我的收藏", keepAlive: true },
    redirect: "/profile/collection/feeds",
    children: [
      {
        path: "feeds",
        component: ProfileCollectionFeeds,
        meta: { title: "收藏的动态" }
      },
      {
        path: "news",
        component: ProfileCollectionNews,
        meta: { title: "收藏的资讯" }
      },
      {
        path: "answers",
        component: ProfileCollectionAnswers,
        meta: { title: "收藏的回答" }
      },
      {
        path: "posts",
        component: ProfileCollectionPosts,
        meta: { title: "收藏的帖子" }
      }
    ]
  },
  {
    name: "ProfileCertificate",
    path: "/profile/certificate",
    component: ProfileCertificate,
    meta: { title: "申请认证" }
  },
  {
    name: "ProfileCertification",
    path: "/profile/certification",
    component: ProfileCertification,
    meta: { title: "认证信息" }
  }
];
