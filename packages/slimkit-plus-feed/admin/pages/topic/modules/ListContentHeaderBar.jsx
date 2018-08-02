import React from 'react';
import PropTypes from 'prop-types';
import withStyles from '@material-ui/core/styles/withStyles';
import Button from '@material-ui/core/Button';
import Tooltip from '@material-ui/core/Tooltip';
import ContentHeaderBar from '../../../components/common/ContentHeaderBar';

// Icons
import RefreshIcon from '@material-ui/icons/RefreshRounded';
import AddIcon from '@material-ui/icons/AddRounded';

import headerBarButtonStyleCreator from './HeaderBarRightButtonCommon.style';
const styles = theme => ({
  headerBarButton: headerBarButtonStyleCreator(theme)
});

class ListContentHeaderBar extends React.Component {
    static propTypes = {
      classes: PropTypes.object.isRequired
    }

    render() {
      const { classes } = this.props;
      return (
        <ContentHeaderBar
          title="话题列表"
          breadcrumbs={['动态', '话题']}
        >
          <Tooltip title="Add a topic">
            <Button className={classes.headerBarButton} variant="fab" color="primary" aria-label="Add a topic" mini>
              <AddIcon />
            </Button>
          </Tooltip>

          <Tooltip title="Refresh page">
            <Button className={classes.headerBarButton} variant="fab" color="primary" aria-label="Refresh page" mini>
              <RefreshIcon />
            </Button>
          </Tooltip>
        </ContentHeaderBar>
      );
    }
}

export default withStyles(styles)(ListContentHeaderBar);
