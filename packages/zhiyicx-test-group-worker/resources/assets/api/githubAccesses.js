import axios from './axios';

const request = axios();

/**
 * Fetch `/github/accesses` data.
 *
 * @return {[type]}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function index() {
  return request.get('/github/accesses', {
    validateStatus: status => status === 200,
  });
}

/**
 * Store a GitHub Access.
 *
 * @param {string} username
 * @param {string} password
 * @return {[type]}
 * @author Seven Du <shiweidu@outlook.com>
 */
export function store(username, password) {
  return request.post('/github/accesses', {username, password}, {
    validateStatus: status => status === 201,
  });
}
