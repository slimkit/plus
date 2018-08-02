import React from 'react';
import PropTypes from 'prop-types';
import withStyles from '@material-ui/core/styles/withStyles';
import Paper from '@material-ui/core/Paper';
import Toolbar from '@material-ui/core/Toolbar';
import HeaderBar from './modules/ListContentHeaderBar';
import styles from './List.styles';

class List extends React.Component {
    static propTypes = {
      classes: PropTypes.object.isRequired
    }

    render() {
      let { classes } = this.props;
      return (
        <div>
          <HeaderBar />
          <Paper className={classes.root}>
            <Toolbar>
              2
            </Toolbar>
          </Paper>
        </div>
      );
    }
}

export default withStyles(styles)(List);
