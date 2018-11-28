import { adminRequest } from '../utils/request-builder';

export function search(keyword)
{
  return adminRequest.get('../../admin/users', {
    params: {
      name: keyword
    },
    validateStatus: status => status === 200
  });
}
