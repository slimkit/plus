import * as api from '@/api/wallet'
import _ from 'lodash'

const state = {
  list: [], // 充值纪录
  cashes: [], // 提现记录
  items: [], // 充值建议金额
  ratio: 100, // 充值比率
  type: [], // 充值类型
  rule: '', // 充值提现规则
}

const getters = {
  getWalletById: state => id => {
    return state.list.filter(wallet => wallet.id === id).pop() || {}
  },
  getCashesById: state => id => {
    return state.cashes.filter(wallet => wallet.id === id).pop() || {}
  },
  rechargeItems: state => {
    const { items = [] } = state
    return items.map(item => item / 100)
  },
}

const TYPES = {
  UPDATE_WALLET: 'UPDATE_WALLET',
}

const mutations = {
  [TYPES.UPDATE_WALLET] (state, payload) {
    Object.assign(state, payload)
  },
}

const actions = {
  /**
   * 获取钱包流水
   * @author mutoe <mutoe@foxmail.com>
   * @returns {Promise<Object[]>}
   */
  async getWalletOrders ({ commit, state }, params) {
    let { data } = await api.getWalletOrders(params)
    const unionList = _.unionBy([...state.list, ...data], 'id')
    commit(TYPES.UPDATE_WALLET, { list: unionList })
    return data || []
  },

  /**
   * 获取钱包配置信息
   * @author mutoe <mutoe@foxmail.com>
   */
  async getWalletInfo ({ commit }) {
    let { data } = await api.getWalletInfo()
    const { labels: items, ratio, rule, recharge_type: type } = data
    commit(TYPES.UPDATE_WALLET, { items, type, ratio, rule })
  },

  /**
   * 发起充值请求
   * @author mutoe <mutoe@foxmail.com>
   * @returns {Promise<string>} url
   */
  async requestRecharge (state, payload) {
    const { data = '' } = await api.postWalletRecharge(payload)
    return data
  },

  /**
   * 发起提现请求
   * @author mutoe <mutoe@foxmail.com>
   * @returns
   */
  async requestWithdraw (state, payload) {
    const { data } = await api.postWalletWithdraw(payload)
    return data
  },

  /**
   * 获取提现列表
   * @author mutoe <mutoe@foxmail.com>
   * @returns {Promise<Object[]>}
   */
  async fetchWithdrawList ({ commit }, payload) {
    const { data } = await api.getWithdrawList(payload)
    commit(TYPES.UPDATE_WALLET, { cashes: data })
    return data
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}
