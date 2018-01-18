import axios from './axios';

const request = axios();

/**
 * Fetch all settings data.
 *
 * @return {[type]}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function settings() {
  return request.get('/settings', {
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
  return request.post('/settings/github', { login, password }, {
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
  return request.delete('/settings/github', {
    validateStatus: status => status === 204,
  });
}
