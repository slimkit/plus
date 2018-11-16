import api from './api'

/**
 * 获取钱包配置信息
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @returns
 */
export function getWalletInfo () {
  return api.get('/wallet', { validateStatus: s => s === 200 })
}

/**
 * 获取钱包流水
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {number} [params.limit]
 * @param {number} [params.after] 最后一条流水的 id
 * @param {string} [params.action] (income: 收入|expenses: 支出)
 * @returns
 */
export function getWalletOrders (params) {
  return api.get('/plus-pay/orders', {
    params,
    validateStatus: s => s === 200,
  })
}

/**
 * 转换为积分
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} data
 * @param {number} data.amount
 * @returns
 */
export function postTransform (data) {
  return api.post('/plus-pay/transform', data, {
    validateStatus: s => s === 201,
  })
}

/**
 * 发起充值请求
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} data
 * @param {string} data.type 充值方式
 * @param {number} data.amount 充值金额(单位：RMB分)
 * @param {number} data.from 来自哪个端 h5固定为2
 * @returns
 */
export function postWalletRecharge (data) {
  const url = '/walletRecharge/orders'
  data = Object.assign(data, { from: 2 })
  return api.post(url, data, { validateStatus: s => s === 201 })
}

/**
 * 发起提现请求
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} data
 * @param {number} data.amount
 * @param {string} data.type
 * @param {string} data.account
 * @returns
 */
export function postWalletWithdraw (data) {
  return api.post('/plus-pay/cashes', data, { validateStatus: s => s === 201 })
}

/**
 * 获取提现列表
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {number} params.limit
 * @param {number} params.after
 * @returns
 */
export function getWithdrawList (params) {
  return api.get('/plus-pay/cashes', {
    params,
    validateStatus: s => s === 200,
  })
}
