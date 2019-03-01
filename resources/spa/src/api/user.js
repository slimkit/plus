import { limit } from './index'
import api from './api'
import store from '@/stores'
import $Message from '@/plugins/message-box'
import lstore from '@/plugins/lstore/lstore.js'

/**
 * 定义用户对象
 * @typedef {Object} UserObject
 * @property {number} id
 */

const resArray = { data: [] }

/**
 * 关注 || 取关 操作
 * @author jsonleex <jsonlseex@163.com>
 * @export
 * @param  {string} options.status  ["unFollow", "eachFollow", "follow"]
 * @param  {number} options.id      user.id
 * @returns
 */
export const followUserByStatus = ({ status, id }) => {
  let method
  switch (status) {
    case 'unFollow':
      method = 'PUT'
      break
    case 'eachFollow':
      method = 'DELETE'
      break
    case 'follow':
      method = 'DELETE'
      break
  }
  if (!method || !id) throw new Error('参数错误')
  let url = `/user/followings/${id}`
  const res = {
    status: true,
    follower: method === 'PUT',
  }

  return api({
    method,
    url,
    validateStatus: s => s === 204,
  }).then(() => {
    store.commit('SAVE_USER', { id, follower: res.follower })
    return res.follower
  })
}

/**
 * 搜索用户
 * @author jsonleex <jsonlseex@163.com>
 * @export
 * @param  {string} key
 * @param  {number} after 偏移量 用于翻页
 * @returns {Promise<UserObject[]>}
 */
export function searchUserByKey (key, after) {
  if (!key) return Promise.resolve(resArray)

  const params = { keyword: key }
  after > 0 && (params.offset = after)
  return api
    .get('/user/search', { params })
    .catch(() => Promise.resolve(resArray))
}

/**
 * 找人
 * @author jsonleex <jsonlseex@163.com>
 * @export
 * @param  {string} type
 * @param  {Object} config
 * @returns {Promise<UserObject[]>}
 */
export const findUserByType = (type, params) => {
  const typeMap = ['recommends', 'populars', 'latests', 'find-by-tags']
  if (!typeMap.includes(type)) {
    return Promise.reject(new Error('参数不正确'))
  }
  return api.get(`/user/${type}`, { params }).catch(() => {
    // 错误处理
    return resArray
  })
}

/**
 * 查找附近的人
 * @author jsonleex <jsonlseex@163.com>
 * @export
 * @param {number} options.lng: longitude    经度
 * @param {number} options.lat: latitude     纬度
 * @param {number} page                      当前页
 * @returns {Promise<UserObject[]>}
 */
export const findNearbyUser = ({ lng: longitude, lat: latitude }, page = 0) => {
  const params = {
    limit,
    longitude,
    latitude,
  }
  page > 0 && (params.page = page)

  return api.get('around-amap', { params })
    .catch(() => resArray)
}

/**
 * 获取用户基本信息 优先返回本地数据
 * @author jsonleex <jsonlseex@163.com>
 * @export
 * @param {number} id
 * @param {boolean} [force=false]
 * @returns {Promise<UserObject>}
 */
export async function getUserInfoById (id, force = false) {
  const user = store.state.USERS[`user_${id}`]
  if (user && !force) return user

  return api
    .get(`/users/${id}`, { validateStatus: s => [404, 201, 200].includes(s) })
    .then(({ data }) => {
      data = data.id ? data : {}
      store.commit('SAVE_USER', data)
      return store.state.USERS[`user_${id}`]
    })
}

/**
 * 获取用户列表
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {number} [params.limit]
 * @param {string} [params.order]
 * @param {number} [params.since]
 * @param {string} [params.name]
 * @param {string} [params.fetch_by]
 * @param {string} [params.id]
 * @returns {Promise<UserObject[]>}
 */
export function getUserList (params) {
  const url = '/users'
  return api.get(url, { params, validateStatus: s => s === 200 })
}

/**
 * 获取用户粉丝列表
 * @author jsonleex <jsonlseex@163.com>
 * @export
 * @param  {number} options.uid
 * @param  {string} options.type
 * @param  {number} options.offset
 * @returns {Promise<UserObject[]>}
 */
export function getUserFansByType ({ uid, type, offset = 0 }) {
  const params = {
    limit,
    offset,
  }
  return api
    .get(`/users/${uid}/${type}`, { params })
    .then(({ data = [] }) => data)
    .catch(() => {
      return []
    })
}

/**
 * 注册
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} payload
 * @param {string} payload.login login account
 * @param {string} payload.password
 * @returns {Promise<boolean>}
 */
export function signinByAccount (payload) {
  return api.post('/auth/login', payload, { validateStatus: s => s > 0 })
    .then(({ data: { message, access_token: token }, status }) => {
      switch (status) {
        case 422:
          $Message.error(message)
          return false
        case 200:
          lstore.setData('H5_ACCESS_TOKEN', `Bearer ${token}`)
          store.dispatch('fetchUserInfo')
          return true
      }
    })
    .catch(() => false)
}

/**
 * 刷新用户信息
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @returns
 */
export function fetchUserInfo () {
  return api.get('/user', { validateStatus: s => s === 200 })
}

/**
 * 上传用户主页背景图
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {File} file 图像文件
 */
export function uploadUserBanner (file) {
  const formData = new FormData()
  formData.append('image', file)
  return api.post(`/user/bg`, formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
    validateStatus: s => s === 204,
  })
}

/**
 * 打赏用户
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} userId
 * @param {Object} data
 * @param {number} data.amount 打赏金额
 * @returns
 */
export function rewardUser (userId, data) {
  const url = `/user/${userId}/new-rewards`
  return api.post(url, data, { validateStatus: s => s === 201 })
}

/**
 * 申请认证
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} payload
 * @param {string} payload.type 'user' or 'org'
 * @param {string} payload.name 真实姓名 or 负责人名字
 * @param {string} payload.phone 用户联系方式 or 负责人联系方式
 * @param {string} payload.number 身份证号码 or 营业执照注册号
 * @param {string} payload.desc 认证描述
 * @param {string} [payload.org_name] 企业或机构名称
 * @param {string} [payload.org_address] 企业或机构地址
 * @param {number[]} [payload.files]
 * @returns
 */
export function postCertification (payload) {
  const url = '/user/certification'
  return api.post(url, payload, { validateStatus: s => s === 201 })
}

export function patchCertification (payload) {
  const url = '/user/certification'
  return api.patch(url, payload, { validateStatus: s => s === 201 })
}

/**
 * 获取用户认证信息
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @returns
 */
export function getUserVerifyInfo () {
  return api.get('/user/certification', { validateStatus: s => s === 200 })
}

/**
 * 举报用户
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} userId
 * @param {string} reason
 * @returns
 */
export function reportUser (userId, reason) {
  const url = `/report/users/${userId}`
  return api.post(url, { reason }, { validateStatus: s => s === 201 })
}

/**
 * 获取用户标签
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {number} userId
 * @returns
 */
export function getUserTags (userId) {
  const url = `/users/${userId}/tags`
  return api.get(url, { validateStatus: s => s === 200 })
}

/**
 * 获取好友列表
 *
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} [params]
 * @param {number} [params.offset]
 * @param {number} [params.limit]
 * @param {string} [params.keyword]
 * @returns {UserObject[]}
 */
export function getUserFriends (params) {
  const url = '/user/follow-mutual'
  return api.get(url, { params, validateStatus: s => s === 200 })
}
