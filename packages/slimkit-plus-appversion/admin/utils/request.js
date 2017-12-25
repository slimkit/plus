import axios from 'axios';

const {
  api,
  base_url,
  token
} = window.Version;

// Export a method to create the requested address.
export const createRequestURI = PATH => `${base_url}/${PATH}`;

// Created the request address of API.
export const createAPI = PATH => `${api}/${PATH}`;

// 注入access-token验证
export const addAccessToken = () => {
  // 如果有才发送
  const {
    token = ''
  } = storeLocal.get('UserLoginInfo') || {};
  let _token = token ? `Bearer ${token}` : '';
  axios.defaults.headers.common = {
    'Authorization': _token,
    'Accept': 'application/json'
  };
  return axios;
};

export default axios;