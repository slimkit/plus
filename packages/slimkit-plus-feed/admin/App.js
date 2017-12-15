/**
 * The file is Plus `feed-component` app.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types'
import { matchPath, withRouter } from 'react-router';
import { Route } from 'react-router-dom';
import withStyles from 'material-ui/styles/withStyles';
import AppBar from 'material-ui/AppBar';
import Tabs, { Tab } from 'material-ui/Tabs';
import Home from './components/Home';
import Feed from './components/Feed';
import Comment from './components/Comment';
import PayControl from './components/PayControl';
import DeleteFeed from './components/DeletedFeed';
// import DeleteComment from './components/DeletedComment';

const styles = () => ({
  root: {}
});

class App extends Component
{

  static propTypes = {
    match: PropTypes.object.isRequired,
    location: PropTypes.object.isRequired,
    history: PropTypes.object.isRequired,
    classes: PropTypes.object.isRequired,
  }

  /**
   * Get router pathname.
   *
   * @return {String}
   * @author Seven Du <shiweidu@outlook.com>
   */
  getPathname() {
    const { location: { pathname = '/' } } = this.props;

    return pathname;
  }

  /**
   * Match pathname.
   *
   * @return {Integer} route's index.
   * @author Seven Du <shiweidu@outlook.com>
   */
  matchPath() {
    const pathname = this.getPathname();

    if (matchPath(pathname, { exact: true })) {
      return 'root';
    } else if (matchPath(pathname, { path: '/feeds' })) {
      return 'feeds';
    } else if (matchPath(pathname, { path: '/comments' })) {
      return 'comments';
    } else if (matchPath(pathname, { path: '/paycontrol'})) {
      return 'paycontrol';
    } else if (matchPath(pathname, { path: '/deleteFeeds'})) {
      return 'deleteFeeds';
    } 
    // else if(matchPath(pathname, { path: '/deleteComments'})) {
    //   return 'deleteComments'
    // }

    return 0;
  }

  /**
   * Route change handle.
   *
   * @param {Object} event
   * @param {Integer} index
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  handleChange = (event, value) => {
    const { history: { replace } } = this.props;
    this.setState({ value });
    switch (value) {
      case 'comments':
        replace('/comments');
        break;

      case 'feeds':
        replace('/feeds');
        break;
        
      case 'paycontrol':
        replace('/paycontrol');
        break;

      // case 'deleteComments':
      //   replace('/deleteComments');
      //   break;

      case 'deleteFeeds':
        replace('/deleteFeeds');
        break;

      case 'root':
      default:
        replace('/');
        break;
    }
  };

  /**
   * Rende the component view.
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   */
  render() {
    const { classes } = this.props;
    return (
      <div className={classes.root}>
        <AppBar position="static">
          <Tabs
            value={this.matchPath()}
            onChange={this.handleChange}
          >
            <Tab label="基础信息" value="root" />
            <Tab label="动态管理" value="feeds" />
            <Tab label="评论管理" value="comments" />
            <Tab label="付费开关" value="paycontrol" />
            <Tab label="动态回收站" value="deleteFeeds" />
          </Tabs>
        </AppBar>

        <Route exact path="/" component={Home} />
        <Route path="/feeds" component={Feed} />
        <Route path='/comments' component={Comment} />
        <Route path='/paycontrol' component={PayControl} />
        <Route path="/deleteFeeds" component={DeleteFeed} />
      </div>
    );
  }
}

export default withRouter(
  withStyles(styles)(App)
);
