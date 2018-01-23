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

/**
 * Register a interval event.
 *
 * @return {void} [description]
 * @author Seven Du <shiweidu@outlook.com>
 */
window.setInterval(function () {
  axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.accessToken;
  axios.get('/../v2/auth/refresh').then(({ data }) => {
    window.accessToken = data.access_token;
  }).catch(() => {});
}, (window.expires_in - 120) * 1000);

export default function() {
  axios.defaults.headers.common['Authorization'] = 'Bearer ' + window.accessToken;

  return axios;
};
