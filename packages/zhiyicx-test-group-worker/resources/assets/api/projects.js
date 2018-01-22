import axios from './axios';

const request = axios();

export function store(data) {
  return request.post('/projects', data, {
    validateStatus: status => status === 201,
  });
}

export function all() {
  return request.get('/projects', {
    validateStatus: status => status === 200,
  });
}

export function show(id) {
  return request.get('/projects/'+id, {
    validateStatus: status => status === 200,
  });
}

export function readme(id) {
  return request.get(`/projects/${id}/readme`, {
    validateStatus: status => status === 200,
  });
}

export function update(id, data) {
  const { name, desc } = data;
  return request.patch(`/projects/${id}`, { name, desc }, {
    validateStatus: status => status === 204,
  });
}

export function destory(id) {
  return request.delete(`/projects/${id}`, {
    validateStatus: status => status === 204,
  });
}
