/**
 * The store is storage user list.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import { USER_UPDATE, USER_DELETE } from '../types';
import { USERS } from '../getter-types';

const state = {
  users: []
};

const mutations = {
  /**
   * 更新用户资料.
   *
   * @param {Object} state
   * @param {Object} user
   *
   * @author Seven Du <shiweidu@outlook.com>
   */
  [USER_UPDATE] (state, users) {
    let _users = [];

    state.users.forEach(user => {
      let __user = user;
      users.forEach((_user, index) => {
        if (_user.id === user.id) {
          __user = {...user, ..._user};
          delete users[index];
        }
      });

      _users.push(__user);
    });

    state.users = _users;
  },
  /**
   * 删除指定用户.
   *
   * @param {Object} state
   * @param {Number} user_id
   *
   * @author Seven Du <shiweidu@outlook.com>
   */
  [USER_DELETE] (state, user_id) {
    let users = [];
    state.users.forEach(user => {
      if (user.id !== user_id) {
        users.push(user);
      }
    });

    state.users = users;
  }
};

const actions = {
  /**
   * 更新用户操作，支持批量更新.
   *
   * @param {Object} context
   * @param {Function} cb
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  [USER_UPDATE]: (context, cb) => cb(
    (...users) => context.commit(USER_UPDATE, users),
    context
  ),
  /**
   * 删除指定用户.
   *
   * @param {Object} context
   * @param {Function} cb
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  [USER_DELETE]: (context, cb) => cb(
    user_id => context.commit(USER_DELETE, user_id),
    context
  )
};

const getters = {
  /**
   * 获取用户列表
   *
   * @param {Array} options.users
   * @return {Array}
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  [USERS]: ({ users }) => users
};

const store = { state, mutations, actions, getters };

export default store;
