/**
 * 消息页面组件
 */

import i18n from '@/i18n'

const MessageBase = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/MessageBase.vue')
const MessageHome = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/MessageHome.vue')

// 消息
const MessageSystem = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/MessageSystem.vue')
const MessageComments = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/MessageComments.vue')
const MessageLikes = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/MessageLikes.vue')
const AuditList = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/list/AuditList')
const feedCommentAudit = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/children/audits/feedCommentAudit')

// 聊天
const ChatList = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/ChatList.vue')
const ChatRoom = () =>
  import(/* webpackChunkName: 'message' */ '@/page/message/ChatRoom.vue')

export default [
  {
    path: '/message',
    component: MessageBase,
    redirect: '/message/list',
    meta: {
      requiresAuth: true,
    },
    children: [
      {
        path: 'list',
        name: 'MessageHome',
        component: MessageHome,
        meta: {
          title: i18n.t('message.notice'),
          keepAlive: true,
        },
      },
      {
        path: 'chats',
        name: 'ChatList',
        component: ChatList,
        meta: {
          title: i18n.t('message.chat.name'),
        },
      },
    ],
  },
  {
    path: '/message/chats/:chatId(\\d+)',
    name: 'ChatRoom',
    component: ChatRoom,
    meta: {
      title: i18n.t('message.chat.name'),
    },
  },
  {
    path: '/message/system',
    component: MessageSystem,
    meta: {
      title: i18n.t('message.system.name'),
      requiresAuth: true,
      keepAlive: true,
    },
  },
  {
    path: '/message/comments',
    name: 'MessageComments',
    component: MessageComments,
    meta: {
      title: i18n.t('message.comment.name'),
      requiresAuth: true,
      keepAlive: true,
    },
  },
  {
    path: '/message/likes',
    name: 'MessageLikes',
    component: MessageLikes,
    meta: {
      title: i18n.t('message.like.name'),
      requiresAuth: true,
      keepAlive: true,
    },
  },
  {
    path: '/message/audits',
    component: AuditList,
    meta: {
      title: i18n.t('message.audit.name'),
      requiresAuth: true,
    },
  },
  {
    path: '/message/audits',
    component: AuditList,
    redirect: '/message/audits/feedcomments',
    meta: {
      title: i18n.t('message.audit.name'),
      requiresAuth: true,
    },
    children: [
      {
        name: 'auditFeedComments',
        path: 'feedcomments',
        component: feedCommentAudit,
      },
    ],
  },
]
