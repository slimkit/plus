import { limit } from './index'
import api from './api'

/**
 * 获取话题列表
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {string} [params.q] 搜索关键字
 * @param {number} [params.limit]
 * @param {string} [params.direction=desc]
 * @param {number} [params.index=0]
 * @param {string} [params.only] 是否热门 'hot'
 * @returns
 */
export function getTopicList (params) {
  if (!params.limit) params.limit = limit
  if (params.q === '') return Promise.resolve({ data: [] })
  const url = '/feed/topics'
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 创建话题
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} data
 * @param {string} data.name 话题名称
 * @param {string} [data.desc] 描述
 * @param {string} [data.logo] file node 节点
 * @returns
 */
export function createTopic (data) {
  const url = '/feed/topics'
  return api.post(url, data, { validateStatus: s => s === 201 })
}

/**
 * 获取动态详情
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} topicId
 * @returns
 */
export function getTopicDetail (topicId) {
  const url = `/feed/topics/${topicId}`
  return api.get(url, { validateStatus: s => s === 200 })
}

/**
 * 获取话题相关动态
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} topicId
 * @param {Object} params
 * @param {Object} [params.limit=15]
 * @param {Object} [params.direction=desc]
 * @param {Object} [params.index=0]
 * @returns {FeedObject[]}
 */
export function getTopicFeeds (topicId, params) {
  const url = `/feed/topics/${topicId}/feeds`
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 关注话题
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} topicId
 * @returns
 */
export function followTopic (topicId) {
  const url = `/user/feed-topics/${topicId}`
  return api.put(url, {}, { validateStatus: s => s === 204 })
}

/**
 * 取消关注话题
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} topicId
 * @returns
 */
export function unfollowTopic (topicId) {
  const url = `/user/feed-topics/${topicId}`
  return api.delete(url, { validateStatus: s => s === 204 })
}

/**
 * 参与话题的人
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} topicId
 * @param {Object} params
 * @param {number} [params.limit=15]
 * @param {number} [params.offset]
 * @returns {UserObject[]}
 */
export function getTopicParticipants (topicId, params) {
  const url = `/feed/topics/${topicId}/participants`
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 举报话题
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} topicId
 * @param {string} message 举报理由
 * @returns
 */
export function reportTopic (topicId, message) {
  const url = `/user/report-feed-topics/${topicId}`
  return api.put(url, { message }, { validateStatus: s => s === 204 })
}

/**
 * 编辑话题
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} topicId
 * @param {Object} payload
 * @param {string} payload.desc
 * @param {string} payload.logo
 * @returns
 */
export function editTopic (topicId, payload) {
  const url = `/feed/topics/${topicId}`
  return api.patch(url, payload, { validateStatus: s => s === 204 })
}
