/**
 * The file is Plus `feed-component` app.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { withRouter, matchPath } from 'react-router';
import { Route, NavLink } from 'react-router-dom';
import withStyles from '@material-ui/core/styles/withStyles';
import AppBar from '@material-ui/core/AppBar';
import Tabs from '@material-ui/core/Tabs';
import Tab from '@material-ui/core/Tab';
import Home from './components/Home';
import Feed from './components/Feed';
import Comment from './components/Comment';
import PayControl from './components/PayControl';
import DeleteFeed from './components/DeletedFeed';
import TopicPage from './pages/topic';

const styles = () => ({
  root: {}
});

const routes = [
  { label: '基础信息', route: '/', component: Home, exact: true },
  { label: '动态管理', route: '/feeds', component: Feed },
  { label: '话题管理', route: '/topic', component: TopicPage },
  { label: '评论管理', route: '/comments', component: Comment },
  { label: '付费开关', route: '/paycontrol', component: PayControl },
  { label: '动态回收站', route: '/deleteFeeds', component: DeleteFeed },
];

class App extends Component {

  static propTypes = {
    location: PropTypes.object.isRequired,
    classes: PropTypes.object.isRequired,
  }

  handleActiveRoute = () => {
    let { location: { pathname } } = this.props;

    for (let tab of routes) {
      let matched = matchPath(pathname, { ...tab, path: tab.route });
      if (matched) {
        return matched.path;
      }
    }
  }

  /**
   * Rende the component view.
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   */
  render() {
    let { classes} = this.props;

    return (
      <div className={classes.root}>
        <AppBar position="fixed">
          <Tabs value={this.handleActiveRoute()} >
            {routes.map(tab => (
              <Tab
                {...tab}
                lebel={tab.label}
                value={tab.route}
                to={tab.route}
                component={NavLink}
                key={tab.route}
              />
            ))}
          </Tabs>
        </AppBar>

        <main style={{ paddingTop: 48 }}>
          {routes.map(tab => (
            <Route {...tab} key={tab.route} path={tab.route} component={tab.component} />
          ))}
        </main>
      </div>
    );
  }
}

export default withRouter(
  withStyles(styles)(App)
);
