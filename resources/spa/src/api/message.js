import api from './api'

// 获取系统通知
export function getNotificationStatistics () {
  return api.get(`/user/notification-statistics`)
}

// 获取未读审核通知数量
export function getUnreadCounts () {
  return api.get('/user/counts')
}

/**
 * 获取通知列表
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {string} params.type
 * @param {number} [params.page=1]
 * @returns
 */
export function getNotification (params) {
  return api.get('/user/notifications', { params })
}

/**
 * 标记通知已读
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {string} type
 * @returns
 */
export function resetNotificationCount (type) {
  return api.patch('/user/notifications', {}, { params: { type } })
}
