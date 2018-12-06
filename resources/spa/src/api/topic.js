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
