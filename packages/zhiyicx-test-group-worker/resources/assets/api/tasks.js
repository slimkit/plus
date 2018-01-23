import axios from './axios';

export function all(query = {}) {
  return axios().get('/tasks', {
    query,
    validateStatus: status => status === 200,
  });
}

export function list(project, query = {}) {
  return axios().get(`/projects/${project}/tasks`, {
    query,
    validateStatus: status => status === 200,
  });
}

export function create(project, data) {
  return axios().post(`/projects/${project}/tasks`, data, {
    validateStatus: status => status === 201,
  });
}
