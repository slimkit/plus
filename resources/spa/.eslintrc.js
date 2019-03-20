module.exports = {
  root: true,
  env: {
    node: true,
  },
  extends: [
    'plugin:vue/recommended',
    '@vue/standard',
  ],
  parserOptions: {
    parser: 'babel-eslint',
  },
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'warn',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'warn',
    'comma-dangle': ['error', 'always-multiline'],
    'vue/max-attributes-per-line': ['error', { singleline: 2 }],
    'vue/singleline-html-element-content-newline': ['off'],
    'vue/no-use-v-if-with-v-for': ['off'],
    'vue/no-v-html': ['off'],
  },
}
