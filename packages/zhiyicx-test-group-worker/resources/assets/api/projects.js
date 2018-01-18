import axios from './axios';

const request = axios();

export function store(data) {
  return request.post('/projects', data, {
    validateStatus: status => status === 201,
  });
};
