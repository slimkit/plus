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

export function setPinned(id, day, payload) {
  return adminRequest.post(`${id}/pinned`, { ...payload, day }, {
    validateStatus: status => status === 201,
  });
}

export function unsetPinned(id) {
  return adminRequest.delete(`feeds/${id}/pinned`, {
    validateStatus: status => status === 204,
  });
}

export function passPinned(pinnedId) {
  return adminRequest.patch(`pinned/${pinnedId}`, { action: 'accept' }, {
    validateStatus: status => status === 201,
  });
}

export function rejectPinned(pinnedId) {
  return adminRequest.delete(`feeds/pinneds/${pinnedId}`, {
    validateStatus: status => status === 204,
  });
}
