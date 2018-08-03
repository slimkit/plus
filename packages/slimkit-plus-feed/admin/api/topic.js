import { adminRequest } from '../utils/request-builder';

export function list(options = {}) {
  return adminRequest.get('topics', {
    validateStatus: status => status === 200,
    ...options
  });
}
