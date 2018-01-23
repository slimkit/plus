import axios from './axios';

/**
 * Fetch all settings data.
 *
 * @return {[type]}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function settings() {
  return axios().get('/settings', {
    validateStatus: status => status === 200,
  });
}

/**
 * Bind GitHub Access.
 *
 * @param {string} login
 * @param {string} password
 * @return {[type]}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function githubBind(login, password) {
  return axios().post('/settings/github', { login, password }, {
    validateStatus: status => status === 201,
  });
}

/**
 * Unbing GitHub Access.
 *
 * @return {[type]}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function unbindGitHub() {
  return axios().delete('/settings/github', {
    validateStatus: status => status === 204,
  });
}
