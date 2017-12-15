import axios from 'axios';

const { baseURL } = window.FEED;

// Export a method to create the requested address.
export const createRequestURI = path => `${baseURL}/${path}`;

/**
 * location.search query string to object.
 *
 * @param {string} search
 * @return {Object}
 * @author Seven Du <shiweidu@outlook.com>
 */
export const queryString = search => {
  let queryString = search || typeof location !== 'undefined' && location.search;
  if (!queryString) {
    return {};
  }

  queryString = queryString.trim().replace(/^(\?)/, '');
  queryString = queryString.split('&');

  let query = {};
  queryString.forEach(function(q) {
    let segment = q.split('=');
    query[segment[0]] = segment.length > 1 ? segment[1] : true;
  });

  return query;
};

axios.defaults.headers.common = {
  'X-Requested-With': 'XMLHttpRequest'
};

export default axios;
