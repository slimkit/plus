const login = (access, password, cb) => {};
const getToken = () => localStorage.access_token;
const loggedIn = () => !!getToken();

/**
 * 退出登录方法
 *
 * @param {Function} cb 回掉方法
 *
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
const logout = (cb = () => {}) => {
  delete localStorage.access_token;
  delete localStorage.refresh_token;
  delete localStorage.expires;
  delete localStorage.created_at;
  delete localStorage.user_id;
  cb();
};

/**
 * auth验证器.
 *
 * @param {string} to 跳转地址
 * @param {object} from 提交数据
 * @param {Function} next 下一步执行回掉
 *
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
export function requireAuth (to, from, next) {
  if (!loggedIn()) {
    next({
      path: '/login',
      query: {
        redirect: to.fullPath
      }
    });
  } else {
    next();
  }
};

export default {
  login, getToken, loggedIn, logout
};
