import { adminRequest } from '../utils/request-builder';

export function list(options = {}) {
  return adminRequest.get('topics', {
    validateStatus: status => status === 200,
    ...options
  });
}

export function add({ name, desc }) {
  return adminRequest.post('topics', { name, desc }, {
    validateStatus: status => status === 201
  });
}

export function update({ id, ...form }) {
  return adminRequest.put(`topics/${id}`, form, {
    validateStatus: status => status === 204
  });
}
