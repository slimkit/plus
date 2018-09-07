/*
|-----------------------------------------------
| Plus feed-component admin application.
|-----------------------------------------------
|
| The file is entry.
|
| @author Seven Du <shiweidu@outlook.com>
|
*/

import React from 'react';
import { render } from 'react-dom';
import { HashRouter as Router } from 'react-router-dom';
import theme from './theme';
import { MuiThemeProvider } from '@material-ui/core/styles';
import CssBaseline from '@material-ui/core/CssBaseline';
import App from 'App';

/**
 * The app entry component.
 *
 * @return {Function}
 * @author Seven Du <shiweidu@outlook.com>
 */

const Main = () => (
  <React.Fragment>
    <CssBaseline />
    <MuiThemeProvider theme={theme}>
      <Router>
        <App />
      </Router>
    </MuiThemeProvider>
  </React.Fragment>
);

document.addEventListener('DOMContentLoaded', () => {
  // Rende app.
  render(
    <Main />,
    document.getElementById('app')
  );

});
