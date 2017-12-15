import Vue from 'vue';
import CreateApp from './components';
import App from './App';

/**
 * Create app.
 *
 * @param {Object} configure
 * @return {Mixed}
 * @author Seven Du <shiweidu@outlook.com>
 */
const createApp = configure => new Vue(configure);

createApp({
  el: '#app',
  render: h => h(App),
});
