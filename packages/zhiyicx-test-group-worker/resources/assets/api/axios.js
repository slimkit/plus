import axios from 'axios';

axios.defaults.baseURL = window.apiBaseUrl;

axios.interceptors.response.use(function (response) {
  return response;
}, function (error) {
  if (error.response && error.response.status === 401) {
    window.location.reload();
  }
  return Promise.reject(error);
});

export default function() {
  axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.accessToken;

  return axios;
};
