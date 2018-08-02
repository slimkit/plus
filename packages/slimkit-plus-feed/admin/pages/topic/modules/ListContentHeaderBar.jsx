import React from 'react';
import PropTypes from 'prop-types';
import withStyles from '@material-ui/core/styles/withStyles';
import Button from '@material-ui/core/Button';
import Tooltip from '@material-ui/core/Tooltip';
import ContentHeaderBar from '../../../components/common/ContentHeaderBar';

// Icons
import RefreshIcon from '@material-ui/icons/RefreshRounded';
import AddIcon from '@material-ui/icons/AddRounded';
import SearchIcon from '@material-ui/icons/Search';
import CloseIcon from '@material-ui/icons/CloseRounded';

import headerBarButtonStyleCreator from './HeaderBarRightButtonCommon.style';
const styles = theme => ({
  headerBarButton: headerBarButtonStyleCreator(theme)
});

class ListContentHeaderBar extends React.Component {
    static propTypes = {
      classes: PropTypes.object.isRequired,
      handleSearchBarToggle: PropTypes.func.isRequired
    }

    render() {
      const { classes, handleSearchBarToggle } = this.props;
      return (
        <ContentHeaderBar
          title="话题列表"
          breadcrumbs={['动态', '话题']}
        >
          <Tooltip title="搜索条切换">
            <Button
              className={classes.headerBarButton}
              variant="fab"
              color="secondary"
              mini
              onClick={handleSearchBarToggle}
            >
              <SearchIcon />
            </Button>
          </Tooltip>

          <Tooltip title="创建话题">
            <Button className={classes.headerBarButton} variant="fab" color="primary" mini>
              <AddIcon />
            </Button>
          </Tooltip>

          <Tooltip title="刷新页面">
            <Button className={classes.headerBarButton} variant="fab" mini>
              <RefreshIcon />
            </Button>
          </Tooltip>
        </ContentHeaderBar>
      );
    }
}

export default withStyles(styles)(ListContentHeaderBar);
