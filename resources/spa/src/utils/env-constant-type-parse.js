/**
 * ENV constant parse to boolean.
 * @param {any} value Any type value
 */
export function boolean (value) {
  // eslint-disable-next-line no-eval
  return !!eval(value)
}

/**
 * ENV constant parse to integer.
 * @param {any} value Any type value
 */
export function integer (value) {
  return parseInt(value)
}

/**
 * ENV constant parse to float.
 * @param {any} value Any type value
 */
export function float (value) {
  return parseFloat(value)
}
