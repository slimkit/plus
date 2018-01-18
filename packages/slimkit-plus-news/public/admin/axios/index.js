import apiInstance from './api';
import adminInstance from './admin';

const setDefault = instance => {

  instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

  /**
   * Next we will register the CSRF Token as a common header with Axios so that
   * all outgoing HTTP requests automatically have it attached. This is just
   * a simple convenience so we don't have to attach every token manually.
   */

  const token = document.head.querySelector('meta[name="csrf-token"]');

  if (! token) {
      console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
  }

  instance.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

  return instance;
};

export const admin = setDefault(adminInstance);
export const api = setDefault(apiInstance);
export default { api, admin };
