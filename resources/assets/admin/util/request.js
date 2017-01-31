import axios from 'axios';

const { baseRUL, csrfToken } = window.TS; // This "TS" variable is set from the global variables in the template.

// Export a method to create the requested address.
export const createRequestURI = PATH => `${baseRUL}/${PATH}`;

axios.defaults.headers.common = {
  'X-CSRF-TOKEN': csrfToken,
  'X-Requested-With': 'XMLHttpRequest'
};

export default axios;

// ...
// Read the https://github.com/mzabriskie/axios
