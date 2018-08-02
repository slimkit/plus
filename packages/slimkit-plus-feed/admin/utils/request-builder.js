import axios from 'axios';

export const adminRequest = axios.create({
  baseURL: window.FEED.baseURL
});
