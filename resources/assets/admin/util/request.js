import axios from 'axios';

const { base_url, csrf_token } = window.TS; // This "TS" variable is set from the global variables in the template.

// Export a method to create the requested address.
export const createRequestURI = PATH => `${base_url}/${PATH}`;

axios.defaults.headers.common = {
  'X-CSRF-TOKEN': base_url,
  'X-Requested-With': 'XMLHttpRequest'
};

export default axios;

// ...
// Read the https://github.com/mzabriskie/axios
