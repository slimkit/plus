import axios from 'axios';

// const api = axios.create({
//   baseURL: window.apiBaseUrl,
// });

// api.headers['Authorization'] = 'Bearer '+window.accessToken;

axios.defaults.baseURL = window.apiBaseUrl;

export default function() {
  axios.default.headers.common['Authorization'] = 'Bearer ' + window.accessToken;

  return axios;
};
