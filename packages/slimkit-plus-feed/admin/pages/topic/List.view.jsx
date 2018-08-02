import React from 'react';
import PropTypes from 'prop-types';
import withStyles from '@material-ui/core/styles/withStyles';
import Paper from '@material-ui/core/Paper';
import Toolbar from '@material-ui/core/Toolbar';
import Input from '@material-ui/core/Input';
import InputLabel from '@material-ui/core/InputLabel';
import FormControl from '@material-ui/core/FormControl';
import HeaderBar from './modules/ListContentHeaderBar';
import styles from './List.styles';

class ListView extends React.Component {
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
              <FormControl>
                <InputLabel htmlFor="name-simple">名称</InputLabel>
                <Input />
              </FormControl>
            </Toolbar>
          </Paper>
        </div>
      );
    }
}

export default withStyles(styles)(ListView);
