import { adminRequest } from '../utils/request-builder';

export function list(params) {
  return adminRequest.get('feeds', {
    params,
    validateStatus: status => status === 200,
  });
}

export function destroy(id) {
  return adminRequest.delete(`feeds/${id}`, {
    validateStatus: status => status === 204,
  });
}

export function restore(id) {
  return adminRequest.patch('feeds', {}, {
    params: {
      feed: id,
    },
    validateStatus: status => status === 201
  });
}
