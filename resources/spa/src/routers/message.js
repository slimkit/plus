/**
 * 消息页面组件
 * @Author   Wayne
 * @DateTime 2018-01-29
 * @Email    qiaobin@zhiyicx.com
 * @return   {[type]}            [description]
 */

const notification = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/list/MyNotifications.vue')
const msgComments = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/list/MyComments.vue')
const msgLikes = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/list/MyLikes.vue')
const AuditList = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/list/AuditList')
const feedCommentAudit = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/children/audits/feedCommentAudit')

const ChatList = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message2/chat/chat-list.vue')
const ChatRoom = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message2/chat/chat-room.vue')

// 通知
const MessageIndex = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message2/index.vue')
const info = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message2/info/index.vue')

export default [
  {
    path: '/message',
    component: MessageIndex,
    redirect: '/message/info',
    meta: {
      requiresAuth: true,
    },
    children: [
      {
        path: 'info',
        component: info,
        meta: {
          title: '消息',
        },
      },
      {
        path: 'chats',
        component: ChatList,
        meta: {
          title: '聊天',
        },
      },
    ],
  },
  {
    path: '/chats/:chatId(\\d+)',
    component: ChatRoom,
    meta: {
      title: '对话',
      requiresAuth: true,
    },
  },
  {
    path: '/message/notification',
    component: notification,
    meta: {
      title: '通知',
      requiresAuth: true,
    },
  },
  {
    path: '/message/comments',
    component: msgComments,
    meta: {
      title: '评论我的',
      requiresAuth: true,
    },
  },
  {
    path: '/message/likes',
    component: msgLikes,
    meta: {
      title: '赞过我的',
      requiresAuth: true,
    },
  },
  {
    path: '/message/audits',
    component: AuditList,
    meta: {
      title: '审核列表',
      requiresAuth: true,
    },
  },
  {
    path: '/message/audits',
    component: AuditList,
    redirect: '/message/audits/feedcomments',
    meta: {
      title: '审核列表',
      requiresAuth: true,
    },
    children: [
      {
        name: 'auditFeedComments',
        path: 'feedcomments',
        component: feedCommentAudit,
        meta: {
          title: '动态评论置顶',
        },
      },
    ],
  },
]
