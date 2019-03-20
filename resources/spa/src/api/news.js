import api from './api'
import { limit } from './index'

/**
 * 定义资讯对象
 * @typedef {Object} NewsObject
 * @property {number} id
 * @property {string} title
 * @property {string} subject
 * @property {string} author
 * @property {number} user_id
 * @property {*} more
 */

/**
 * 获取资讯列表
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {number} params.limit
 * @param {number} params.after
 * @param {string} params.key
 * @param {number} params.cate_id
 * @param {number} params.recomended
 * @returns {Promise<NewsObject[]>}
 */
export function getNewsList (params) {
  return api.get('/news', { params, validateStatus: s => s === 200 })
}

/**
 * 获取当前用户投稿列表
 * @Author   Wayne
 * @DateTime 2018-04-28
 * @Email    qiaobin@zhiyicx.com
 * @param {number}   type [类型: 0: 已发布, 1: 待审核, 2: 已驳回]
 * @returns {Promise<NewsObject[]>}
 */
export function getMyNews ({ type = 0, after = 0 }) {
  const params = { type, limit, after }
  return api.get('/user/news/contributes', {
    params,
    validateStatus: s => s === 200,
  })
}

/**
 * 根据 ID 获取资讯
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @returns {Promise<NewsObject>}
 */
export function getNewsById (newsId, { allow404 = false } = {}) {
  const allowedStatus = [200]
  if (allow404) allowedStatus.push(404)
  return api.get(`/news/${newsId}`, { validateStatus: s => allowedStatus.includes(s) })
}

/**
 * 新增投稿
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} categoryId
 * @param {Object} params
 * @param {string} params.title 标题
 * @param {string} params.content 内容
 * @param {number|number[]} params.tags 标签id或其数组
 * @param {string=} params.subject 概要
 * @param {number=} params.image 缩略图
 * @param {string=} params.from 资讯来源
 * @param {string=} params.author 作者
 * @param {string=} params.text_content 纯文本
 * @returns {Promise}
 */
export function postNews (categoryId, params) {
  const url = `/news/categories/${categoryId}/currency-news`
  return api.post(url, params, { validateStatus: s => s === 201 })
}

/**
 * 搜索资讯
 * @author jsonleex <jsonlseex@163.com>
 * @param  {string} key
 * @param  {number} limit
 * @param  {number} after
 * @returns
 */
export function searchNewsByKey (key = '', limit = 15, after = 0) {
  if (!key) return Promise.resolve({ data: [] })
  const params = { key, limit, after }
  return api.get('/news', { params, validateStatus: s => s === 200 })
}

export function getNewsCommentPinneds (after = 0) {
  return api.get('/news/comments/pinneds', {
    limit,
    after,
  })
}

/**
 * 获取某资讯下的全部评论
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @param {Object} params
 * @param {number} params.after
 * @param {number} params.limit
 * @returns
 */
export function getNewsComments (newsId, params) {
  return api.get(`/news/${newsId}/comments`, {
    params,
    validateStatus: s => s === 200,
  })
}

/**
 * 评论一条资讯
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @param {Object} payload
 * @param {string} payload.body
 * @param {number} [payload.reply_user=0]
 * @returns {Promise<{CommentObject}>}
 */
export function postNewsComment (newsId, payload) {
  const url = `/news/${newsId}/comments`
  return api
    .post(url, payload, { validateStatus: s => s === 201 })
    .then(({ data: { comment } }) => comment)
}

/**
 * 申请置顶
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @param {Object} data
 * @param {number} data.amount
 * @param {number} data.day
 * @returns
 */
export function applyTopNews (newsId, data) {
  return api.post(`/news/${newsId}/currency-pinneds`, data, {
    validateStatus: s => s === 201,
  })
}

/**
 * 申请评论置顶
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} payload
 * @param {number} payload.newsId
 * @param {number} payload.commentId
 * @param {Object} data
 * @param {number} data.amount
 * @param {number} data.day
 * @returns
 */
export function applyTopNewsComment ({ newsId, commentId }, data) {
  const url = `/news/${newsId}/comments/${commentId}/currency-pinneds`
  return api.post(url, data, { validateStatus: s => s === 201 })
}

/**
 * 删除评论
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId 资讯 id
 * @param {number} commentId 评论 id
 * @returns {Promise}
 */
export function deleteNewsComment (newsId, commentId) {
  return api.delete(`/news/${newsId}/comments/${commentId}`, {
    validateStatus: s => s === 204,
  })
}

/**
 * 获取资讯点赞列表
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @param {Object} params
 * @param {number} [params.limit]
 * @param {number} [params.after]
 * @returns {Promise<Object[]>}
 */
export function getNewsLikers (newsId, params) {
  const url = `/news/${newsId}/likes`
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 获取资讯打赏列表
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @param {Object} params
 * @param {number} [params.limit]
 * @param {number} [params.offset]
 * @param {string} [params.order] asc 正序 desc 倒序
 * @param {string} [params.order_type] date 按时间 amount 按金额
 * @returns
 */
export function getNewsRewards (newsId, params) {
  const url = `/news/${newsId}/rewards`
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 打赏资讯
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @param {Object} payload
 * @param {number} payload.amount 打赏金额
 * @returns
 */
export function rewardNews (newsId, payload) {
  const url = `/news/${newsId}/new-rewards`
  return api.post(url, payload, { validateStatus: s => s === 201 })
}

/**
 * 获得资讯统计
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @returns
 */
export function getRewardInfo (newsId) {
  const url = `/news/${newsId}/rewards/sum`
  return api.get(url, { validateStatus: s => s === 200 })
}

/**
 * 获取收藏资讯
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {number} params.limit
 * @param {number} params.after
 * @returns {Promise<NewsObject[]>}
 */
export function getCollectedNews (params) {
  const url = '/news/collections'
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 收藏资讯
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @returns
 */
export function collectionNews (newsId) {
  const url = `/news/${newsId}/collections`
  return api.post(url, { validateStatus: s => s === 201 })
}

/**
 * 取消收藏资讯
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @returns
 */
export function uncollectNews (newsId) {
  const url = `/news/${newsId}/collections`
  return api.delete(url, { validateStatus: s => s === 204 })
}

/**
 * 获取一条资讯的相关资讯
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @param {Object} params
 * @param {number} [params.limit]
 * @returns
 */
export function getCorrelations (newsId, params) {
  const url = `/news/${newsId}/correlations`
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 举报动态
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} newsId
 * @param {string} reason 举报理由
 * @returns
 */
export function reportNews (newsId, reason) {
  const url = `/news/${newsId}/reports`
  return api.post(url, { reason }, { validateStatus: s => s === 201 })
}
