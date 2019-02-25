import { limit } from './index'
import api from './api'

/**
 * Query questions list.
 * @author Seven Du <shiweidu@outlook.com>
 * @export
 * @param {Object} params
 * @param {string} params.type default: 'new', options: 'all', 'new', 'hot', 'reward', 'excellent'
 * @param {number} params.limit
 * @param {number} params.offset
 * @param {string} params.subject search keyword
 * @returns
 */
export function queryList (params = {}) {
  return api.get('/questions', {
    params,
    validateStatus: status => status === 200,
  })
}

/**
 * All questions.
 *
 * @param {string} type
 * @param {number} offset
 * @param {number} limit
 * @return {Promise}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function list (type, offset = 0) {
  return queryList({ type, limit, offset })
}
