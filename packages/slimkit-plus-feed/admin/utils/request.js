import axios from 'axios';

const { baseURL } = window.FEED;

// Export a method to create the requested address.
export const createRequestURI = path => `${baseURL}/${path}`;

axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest'
};

export default axios;
