/**
 * The file is Plus `feed-component` app.
 *
 * @author Seven Du <shiweidu@outlook.com>
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { withRouter } from 'react-router';
import { Route, Link } from 'react-router-dom';
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

class App extends Component {

  static propTypes = {
    match: PropTypes.object.isRequired,
    location: PropTypes.object.isRequired,
    history: PropTypes.object.isRequired,
    classes: PropTypes.object.isRequired,
  }

  /**
   * Rende the component view.
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   */
  render() {
    const { classes, location: { pathname = '/' } } = this.props;
    return (
      <div className={classes.root}>
        <AppBar position="fixed">
          <Tabs value={pathname} >
            <Tab label="基础信息" value="/" component={Link} to="/" />
            <Tab label="动态管理" value="/feeds" component={Link} to="/feeds" />
            <Tab label="话题管理" value="/topic" component={Link} to="/topic" />
            <Tab label="评论管理" value="/comments" component={Link} to="/comments" />
            <Tab label="付费开关" value="/paycontrol" component={Link} to="/paycontrol" />
            <Tab label="动态回收站" value="/deleteFeeds" component={Link} to="/deleteFeeds" />
          </Tabs>
        </AppBar>

        <main style={{ paddingTop: 48 }}>
          <Route exact path="/" component={Home} />
          <Route path="/feeds" component={Feed} />
          <Route path='/comments' component={Comment} />
          <Route path='/paycontrol' component={PayControl} />
          <Route path="/deleteFeeds" component={DeleteFeed} />
          <Route path="/topic" component={TopicPage} />
        </main>
      </div>
    );
  }
}

export default withRouter(
  withStyles(styles)(App)
);
