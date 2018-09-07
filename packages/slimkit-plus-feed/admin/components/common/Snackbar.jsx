import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import messageAnalysis from 'plus-message-bundle';
import withStyles from '@material-ui/core/styles/withStyles';
import MaterialSnackbar from '@material-ui/core/Snackbar';
import SnackbarContent from '@material-ui/core/SnackbarContent';
import IconButton from '@material-ui/core/IconButton';
import green from '@material-ui/core/colors/green';
import amber from '@material-ui/core/colors/amber';

// icons
import CheckCircleIcon from '@material-ui/icons/CheckCircle';
import ErrorIcon from '@material-ui/icons/Error';
import InfoIcon from '@material-ui/icons/Info';
import WarningIcon from '@material-ui/icons/Warning';
import CloseIcon from '@material-ui/icons/Close';

const messageIcons = {
  success: CheckCircleIcon,
  error: ErrorIcon,
  warning: WarningIcon,
  info: InfoIcon,
};

class Snackbar extends React.Component {

  static propTypes = {
    open: PropTypes.bool.isRequired,
    onClose: PropTypes.func,
    message: PropTypes.any.isRequired,
    type: PropTypes.oneOf(['success', 'error', 'info', 'warning']),
    classes: PropTypes.object.isRequired,
  }

  static defaultProps = {
    onClose: () => {},
    type: 'info'
  }

  render() {

    let { classes, type, message, onClose, open, ...other } = this.props;
    let Icon = messageIcons[type];

    return (
      <MaterialSnackbar
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'right',
        }}
        open={open}
        onClose={onClose}
        {...other}
      >
        <SnackbarContent
          className={classes[type]}
          message={
            <span className={classes.message}>
              <Icon className={classNames(classes.icon, classes.iconVariant)} />
              {messageAnalysis(message).getMessage()}
            </span>
          }
          action={[
            <IconButton
              key="close"
              color="inherit"
              className={classes.close}
              onClick={onClose}
            >
              <CloseIcon className={classes.icon} />
            </IconButton>,
          ]}
        />
      </MaterialSnackbar>
    );
  }
}

export default withStyles(theme => ({
  success: {
    backgroundColor: green[600],
  },
  error: {
    backgroundColor: theme.palette.error.dark,
  },
  info: {
    backgroundColor: theme.palette.primary.dark,
  },
  warning: {
    backgroundColor: amber[700],
  },
  icon: {
    fontSize: 20,
  },
  iconVariant: {
    opacity: 0.9,
    marginRight: theme.spacing.unit,
  },
  message: {
    display: 'flex',
    alignItems: 'center',
  },
}))(Snackbar);
