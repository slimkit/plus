import api from './api'

/**
 * 获取话题列表
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {string} [params.q] 搜索关键字
 * @param {number} [params.limit=15]
 * @param {string} [params.direction=desc]
 * @param {number} [params.index=0]
 * @param {string} [params.only] 是否热门 'hot'
 * @returns
 */
export function getTopicList (params) {
  const url = '/feed/topics'
  return api.get(url, { params, validateStatus: s => s === 200 })
}
