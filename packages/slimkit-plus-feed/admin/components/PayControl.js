/**
 * The file is admin feeds pay-control manage page.
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types';

import withStyles from '@material-ui/core/styles/withStyles';

const styles = (theme) => ({
  root: {
    width: '100%',
    padding: theme.spacing.unit,
    margin: '20px'
  }
});

class PayControl extends Component
{
  static propTypes = {
    classes: PropTypes.object.isRequired,
  };

  state = {
    snackbar: {
      open: false,
      message: '',
      vertical: 'bottom',
      horizontal: 'right',
    },
    open: false,
    textLength: 50,
    payItems: '1,5,10',
    close: {
      open: false,
      ing: false
    }
  };

  render () {
    return (
      <div style={{padding: '20px'}}>
        <p>开源版无此功能，需要使用此功能，请购买正版授权源码，详情访问www.thinksns.com，也可直接咨询：QQ3515923610；电话：18108035545。</p>
      </div>
    );
  }
}

export default withStyles(styles)(PayControl);
