const login = (access, password, cb) => {};

/**
 * 返回用户是否已经登陆
 *
 * @return {[type]} [description]
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
const logged = () => TS.logged;

/**
 * 退出登录方法
 *
 * @param {Function} cb 回掉方法
 *
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
const logout = (cb = () => {}) => {
  TS.logged = false;
  TS.user = null;
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
  if (!logged()) {
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
  login, logged, logout
};
