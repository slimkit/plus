import store from '../store';
import { USER_DELETE } from '../store/types';
import { USER_LOGGED } from '../store/getter-types';
import request, { createRequestURI } from '../util/request';

const login = (access, password) => request.post(
  createRequestURI('login'),
  { phone: access, password },
  { validateStatus: status => status === 201 }
);

/**
 * 返回用户是否已经登陆
 *
 * @return {[type]} [description]
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
const logged = () => store.getters[USER_LOGGED];

/**
 * 退出登录方法
 *
 * @param {Function} cb 回掉方法
 *
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
const logout = (cb) => {
  store.dispatch(USER_DELETE, cb);
};

/**
 * auth验证器.
 *
 * @param {object} to 要跳转的对象地址
 * @param {object} from 上一层的对象地址
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

/**
 * 登录情况下不允许访问的路由前置验证
 *
 * @param {object} to 要跳转的对象地址
 * @param {object} from 上一层的对象地址
 * @param {Function} next 继续执行的异步回调
 *
 * @author Seven Du <shiweidu@outlook.com>
 * @homepage http://medz.cn
 */
export function loggedAuth (to, from, next) {
  if (logged()) {
    next({
      path: from.fullPath
    });
  } else {
    next();
  }
};

export default {
  login, logged, logout
};
