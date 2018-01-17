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
