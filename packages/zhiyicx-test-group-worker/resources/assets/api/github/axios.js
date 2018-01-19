import axios from 'axios';

const instance = axios.create({
  baseURL: 'https://api.github.com',
  // headers: {},
});
instance.basicToken = function () {
  return 'Basic ' + window.githubBasicToken;
};

export default instance;
