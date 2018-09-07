import React from 'react';
import PropTypes from 'prop-types';
import { Link } from 'react-router-dom';
import withStyles from '@material-ui/core/styles/withStyles';
import Button from '@material-ui/core/Button';
import Tooltip from '@material-ui/core/Tooltip';
import ContentHeaderBar from '../../../components/common/ContentHeaderBar';

// Icons
import UndoIcon from '@material-ui/icons/Undo';

import headerBarButtonStyleCreator from './HeaderBarRightButtonCommon.style';
const styles = theme => ({
  headerBarButton: headerBarButtonStyleCreator(theme),
});

class SettingHeaderBar extends React.Component {

  static propTypes = {
    classes: PropTypes.object.isRequired
  }

  render() {
    let { classes } = this.props;

    return (
      <ContentHeaderBar title="设置" breadcrumbs={['动态', '话题']}>
        <Tooltip title="返回列表">
          <Button
            className={classes.headerBarButton}
            variant="fab"
            mini={true}
            component={Link}
            to="/topic"
          >
            <UndoIcon />
          </Button>
        </Tooltip>
      </ContentHeaderBar>
    );
  }
}

export default withStyles(styles)(SettingHeaderBar);
