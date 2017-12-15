/**
 * The file is 「feed」component admin manage entry.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

const runtime = 'plus-component-feed:manage runtime';
console.time(runtime);

import React from 'react';
import { render } from 'react-dom';
import injectTapEventPlugin from 'react-tap-event-plugin';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import { HashRouter as Router } from 'react-router-dom';
import theme from './theme';
import AppComponent from './components/App';

// The app entry.
const App = () => (
  <MuiThemeProvider muiTheme={theme}>
    <Router>
      <AppComponent />
    </Router>
  </MuiThemeProvider>
);

document.addEventListener('DOMContentLoaded', () => {

  // Needed for onTouchTap
  // http://stackoverflow.com/a/34015469/988941
  injectTapEventPlugin();

  render(
    <App />,
    document.getElementById('app')
  );

  console.timeEnd(runtime);
});
