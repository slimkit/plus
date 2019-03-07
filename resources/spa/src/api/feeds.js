import api from './api'
import { limit } from './index'

/**
 * 定义动态对象
 * @typedef {Object} FeedObject
 * @property {number} id
 * @property {number} user_id
 * @property {string} feed_content
 * @property {number[]} images
 * @property {Object[]} topics
 * @property ...others
 */

/**
 * 获取动态列表
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {string} params.type
 * @param {number} params.limit
 * @param {number} params.after
 * @param {number} params.offset
 * @returns {Promise<FeedObject[]>}
 */
export function getFeeds (params) {
  return api.get('/feeds', { params, validateStatus: s => s === 200 })
}

/**
 * 获取动态详情
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {string} feedId
 * @param {boolean} [params.allow404=false] 允许404
 * @returns
 */
export function getFeed (feedId, { allow404 = false }) {
  const allowedStatus = [200]
  if (allow404) allowedStatus.push(404)
  return api.get(`/feeds/${feedId}`, { validateStatus: s => allowedStatus.includes(s) })
}

/**
 * 申请动态置顶
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @param {Object} params
 * @returns {Promise}
 */
export function applyTopFeed (feedId, params) {
  return api.post(`/feeds/${feedId}/currency-pinneds`, params, {
    validateStatus: s => s === 201,
  })
}

/**
 * @description
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @param {Object} params
 * @param {number} [params.limit] limit
 * @param {number} [params.offset] offset
 * @param {string} [params.order] asc 正序 desc 倒序
 * @param {string} [params.oforder_type] date 按时间 amount 按金额
 * @returns
 */
export function getFeedRewards (feedId, params) {
  const url = `/feeds/${feedId}/rewards`
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 获取点赞列表
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @param {Object} params
 * @param {number} [params.limit=20]
 * @param {number} [params.after=0]
 * @returns {Promise<Object[]>}
 */
export function getFeedLikers (feedId, params) {
  const url = `/feeds/${feedId}/likes`
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 打赏动态
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @param {Object} data
 * @param {number} data.amount 打赏金额
 * @returns
 */
export function rewardFeed (feedId, data) {
  const url = `/feeds/${feedId}/new-rewards`
  return api.post(url, data, { validateStatus: s => s === 201 })
}

/**
 * 删除动态
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @returns {Promise}
 */
export function deleteFeed (feedId) {
  return api.delete(`/feeds/${feedId}/currency`, { validateStatus: s => s === 204 })
}

/**
 * 获取当前用户收藏的动态
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {number} params.limit
 * @param {number} params.offset
 * @param {number} [params.user] 用户id
 * @returns {Promise<FeedObject[]>}
 */
export function getCollectedFeed (params) {
  const url = '/feeds/collections'
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 收藏动态
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @returns
 */
export function collectionFeed (feedId) {
  const url = `/feeds/${feedId}/collections`
  return api.post(url, { validateStatus: s => s === 201 })
}

/**
 * 取消收藏动态
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @returns
 */
export function uncollectFeed (feedId) {
  const url = `/feeds/${feedId}/uncollect`
  return api.delete(url, { validateStatus: s => s === 204 })
}

/**
 * 获取单条动态的评论
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @param {Object} params
 * @param {number} params.limit
 * @param {number} params.after
 * @returns
 */
export function getFeedComments (feedId, params) {
  const { limit, after = 0 } = params
  return api.get(`/feeds/${feedId}/comments`, {
    params: { limit, after },
    validateStatus: s => s === 200,
  })
}

/**
 * 获取置顶评论
 * @export
 * @param {number} [after=0]
 * @returns
 */
export function getFeedCommentPinneds (after = 0) {
  return api.get('/user/feed-comment-pinneds', {
    limit,
    after,
  })
}

/**
 * 发表动态评论
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @param {Object} payload
 * @param {string} payload.body 评论内容
 * @param {number} [payload.reply_user] 回复的用户id
 * @returns {Promise<{CommentObject}>}
 */
export function postFeedComment (feedId, payload) {
  const url = `/feeds/${feedId}/comments`
  return api
    .post(url, payload, { validateStatus: s => s === 201 })
    .then(({ data: { comment } }) => comment)
}

/**
 * 评论申请置顶
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} payload 第一个参数
 * @param {number} payload.feedId
 * @param {number} payload.commentId
 * @param {Object} data post入参
 * @param {number} data.amount 置顶总价
 * @param {number} data.day 置顶天数
 * @returns
 */
export function applyTopFeedComment ({ feedId, commentId }, data) {
  const url = `/feeds/${feedId}/comments/${commentId}/currency-pinneds`
  return api.post(url, data, { validateStatus: s => s === 201 })
}

/**
 * 删除动态评论
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @param {number} commentId
 * @returns
 */
export function deleteFeedComment (feedId, commentId) {
  const url = `/feeds/${feedId}/comments/${commentId}`
  return api.delete(url, { validateStatus: s => s === 204 })
}

/**
 * 举报动态
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} feedId
 * @param {Object} data
 * @param {string} reason 举报理由
 * @returns
 */
export function reportFeed (feedId, reason) {
  const url = `/feeds/${feedId}/reports`
  return api.post(url, { reason }, { validateStatus: s => s === 201 })
}
