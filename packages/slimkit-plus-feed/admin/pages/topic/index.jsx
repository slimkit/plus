import React from 'react';
import PropTypes from 'prop-types';
import { Route, Switch } from 'react-router-dom';
import { withStyles } from '@material-ui/core/styles';
import styles from './index.styles';

// Components
import List from './List';
import Settings from './Settings';

class Topic extends React.Component {

  static propTypes = {
    classes: PropTypes.object.isRequired,
    location: PropTypes.object.isRequired
  };

  render() {
    let { classes } = this.props;
    return (
      <div className={classes.root}>
        <Switch>
          <Route exact path="/topic" component={List} />
          <Route path="/topic/settings" component={Settings} />
        </Switch>
      </div>
    );
  }

  componentDidUpdate(prevProps) {
    if (this.props.location !== prevProps.location) {
      window.scrollTo(0, 0);
    }
  }
}

export default withStyles(styles)(Topic);
