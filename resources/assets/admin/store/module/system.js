import { SETTINGS_SYSTEM_UPDATE } from '../types';

const state = {
  php_version: '',
 	os: '',
	server: '',
  db: '',
  port: '',
  root: '',
  agent: '',
  protocol: '',
  method: '',
  laravel_version: '',
  max_upload_size: '',
  execute_time: '',
  server_date: '',
  local_date: '',
  domain_ip: '',
  user_ip: '',
  disk: ''
};

const mutations = {
  [SETTINGS_SYSTEM_UPDATE] (state, site) {
    Object.keys(site).forEach(key => {
      state[key] = site[key];
    });
  }
};

const siteSystem = {
  state,
  mutations
};

export default siteSystem;