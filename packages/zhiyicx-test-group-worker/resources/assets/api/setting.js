import axios from './axios';

const request = axios();

export function settings() {
  return request.get('/settings', {
    validateStatus: status => status === 200,
  });
}
