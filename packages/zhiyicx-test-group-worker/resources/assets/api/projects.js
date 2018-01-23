import axios from './axios';

export function store(data) {
  return axios().post('/projects', data, {
    validateStatus: status => status === 201,
  });
}

export function all() {
  return axios().get('/projects', {
    validateStatus: status => status === 200,
  });
}

export function show(id) {
  return axios().get('/projects/'+id, {
    validateStatus: status => status === 200,
  });
}

export function readme(id) {
  return axios().get(`/projects/${id}/readme`, {
    validateStatus: status => status === 200,
  });
}

export function update(id, data) {
  const { name, desc } = data;
  return axios().patch(`/projects/${id}`, { name, desc }, {
    validateStatus: status => status === 204,
  });
}

export function destory(id) {
  return axios().delete(`/projects/${id}`, {
    validateStatus: status => status === 204,
  });
}
