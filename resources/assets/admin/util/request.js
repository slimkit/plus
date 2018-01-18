import axios from 'axios';

const { baseURL, api, token } = window.TS; // This "TS" variable is set from the global variables in the template.

// Export a method to create the requested address.
export const createRequestURI = PATH => `${baseURL}/${PATH}`;

// Created the request address of API.
export const createAPI = PATH => `${api}/${PATH}`;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

const csrf_token = document.head.querySelector('meta[name="csrf-token"]');

if (csrf_token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf_token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

export default axios;

// ...
// Read the https://github.com/mzabriskie/axios
