import api from './api'
import Message from '@/plugins/message'

/**
 * 获取积分配置信息
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @returns
 */
export function getCurrencyInfo () {
  return api.get('/currency', { validateStatus: s => s === 200 })
}

/**
 * 获取积分流水
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} params
 * @param {number} [params.limit]
 * @param {number} [params.after]
 * @param {string} [params.action] - 筛选类型 [recharge: 充值记录, cash: 提现记录] 默认为全部
 * @param {number} [params.type] 收入类型 [1: 收入, -1: 支出] 默认为全部
 * @returns
 */
export function getCurrencyOrders (params) {
  return api.get('/currency/orders', {
    params,
    validateStatus: s => s === 200,
  })
}

/**
 * 发起充值
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} data
 * @param {string} data.type 充值方式
 * @param {number} data.amount 充值金额(单位：RMB分)
 * @param {number} data.from 来自哪个端 h5固定为2
 * @returns
 */
export function postCurrencyRecharge (data) {
  const url = '/currencyRecharge/orders'
  return api.post(
    url,
    { ...data, from: 2 },
    { validateStatus: s => s === 201 }
  )
}

/**
 * 发起积分提现
 * @author mutoe <mutoe@foxmail.com>
 * @export
 * @param {Object} data
 * @param {number} data.amount
 * @returns
 */
export function postCurrencyWithdraw (data) {
  return api
    .post('/currency/cash', data, {
      validateStatus: s => s === 201,
    })
    .catch(({ response }) => {
      Message.error(response.data)
    })
}
