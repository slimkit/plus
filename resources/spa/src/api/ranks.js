import api from './api'
import { limit } from './index'

/**
 * 获取全站排行榜
 * 排行榜模块接口入参和返回值一致
 * 为节约代码量，以采用一个方法根据不同参数访问不同接口
 *
 * @export
 * @author mutoe <mutoe@foxmail.com>
 * @param {String} rankApi 排行榜类型
 * @param {Object} params 请求参数
 * @param {Number} params.limit 请求条数
 * @param {Number} params.offset 偏移量
 * @returns
 */
export function getRankUsers (rankApi, params = {}) {
  params = Object.assign({ limit, offset: 0 }, params)
  return api
    .get(`${rankApi}`, { params, validateStatus: s => s === 200 })
    .then(({ data }) => data)
}
