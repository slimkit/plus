import React, { Component, PropTypes } from 'react';
import { Tabs, Tab } from 'material-ui/Tabs';
import { matchPath } from 'react-router';
import { Route } from 'react-router-dom';
import Home from './Home';
import Feeds from './Feeds';
import Comments from './Comments';

class AppComponent extends Component {

  static contextTypes = {
    router: PropTypes.object.isRequired,
    muiTheme: PropTypes.object.isRequired,
  };

  handleChange = (value) => {
    const { router: { history: { replace } } } = this.context;
    replace(value);
  }

  getPathname() {
    const { router: { route: { location: { pathname } } } } = this.context;
    return pathname;
  }

  matchPath() {
    const pathname = this.getPathname();

    if (matchPath(pathname, { exact: true })) {
      return '/';
    } else if (matchPath(pathname, { path: '/feeds' })) {
      return '/feeds';
    } else if (matchPath(pathname, { path: '/comments' })) {
      return '/comments';
    }

    return null;
  }

  render() {
    return (
      <div
        style={{
          padding: '58px 16px',
        }}
      >
        <Tabs
          value={this.matchPath()}
          onChange={this.handleChange}
          style={{
            position: 'fixed',
            width: '100%',
            top: 0,
            right: 0,
            left: 0,
          }}
        >
          <Tab label="动态信息" value="/" />
          <Tab label="动态管理" value="/feeds" />
          <Tab label="评论管理" value="/comments" />
        </Tabs>

        <Route exact path="/" component={Home} />
        <Route path="/feeds" component={Feeds} />
        <Route path="/comments" component={Comments} />
      </div>
    );
  }

}

export default AppComponent;
