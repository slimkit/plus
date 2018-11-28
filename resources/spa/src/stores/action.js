import Api from '@/api/api.js'
import * as api from '@/api'
import * as bootApi from '@/api/bootstrappers.js'
import * as userApi from '@/api/user.js'

export default {
  /**
   * 获取启动信息
   * @author mutoe <mutoe@foxmail.com>
   */
  async BOOTSTRAPPERS ({ commit, dispatch }) {
    bootApi.getBootstrappers().then(({ data: bootstrappers = {} }) => {
      commit('BOOTSTRAPPERS', bootstrappers)
      dispatch('currency/updateCurrencyUnit')
    })
  },

  // 获取用户验证信息
  FETCH_USER_VERIFY ({ commit }) {
    return userApi.getUserVerifyInfo().then(({ data = {} }) => {
      commit('SAVE_USER_VERIFY', data)
      return data
    })
  },

  // 注销登录
  SIGN_OUT ({ commit }) {
    try {
      Api.post(
        `/auth/logout`,
        {},
        { validateStatus: s => (s >= 200 && s < 300) || s === 401 }
      )
      commit('SIGN_OUT')
    } catch (e) {
      // no condition here
    }
  },

  // mark useless
  /* async refreshCurUserData({ state, commit }) {
    const localUser = state.CURRENTUSER;
    if (localUser && localUser.token) {
      const {
        data: { access_token: token }
      } = await Api.post("/auth/refresh");
      localUser.token = token;
    }
    commit("SAVE_CURRENTUSER", localUser);
  }, */

  /**
   * 获取用户信息
   * @author mutoe <mutoe@foxmail.com>
   * @returns {Promise<userApi.UserObject>}
   */
  async fetchUserInfo ({ commit }) {
    const { data } = await userApi.fetchUserInfo()
    commit('SAVE_CURRENTUSER', data)
    return data
  },

  /**
   * 上传文件
   * @author mutoe <mutoe@foxmail.com>
   * @param {File} file
   * @returns
   */
  async uploadFile (state, file) {
    const { data } = await api.postFile(file)
    return data.id
  },
}
