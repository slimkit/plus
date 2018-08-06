import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import withStyles from '@material-ui/core/styles/withStyles';
import Button from '@material-ui/core/Button';
import Tooltip from '@material-ui/core/Tooltip';
import CircularProgress from '@material-ui/core/CircularProgress';
import ContentHeaderBar from '../../../components/common/ContentHeaderBar';

// Icons
import RefreshIcon from '@material-ui/icons/RefreshRounded';
import AddIcon from '@material-ui/icons/AddRounded';
import SearchIcon from '@material-ui/icons/Search';

import green from '@material-ui/core/colors/green';
import headerBarButtonStyleCreator from './HeaderBarRightButtonCommon.style';
const styles = theme => ({
  headerBarButton: headerBarButtonStyleCreator(theme),
  fabProgress: {
    color: green[500],
    position: 'absolute',
    top: 0,
    left: 0,
    zIndex: 1,
  },
  wrapper: {
    position: 'relative',
    display: 'inline-block'
  },
  zoreRightMargin: {
    marginRight: 0
  }
});

class ListContentHeaderBar extends React.Component {
  static propTypes = {
    classes: PropTypes.object.isRequired,
    handleSearchBarToggle: PropTypes.func.isRequired,
    handleRefresh: PropTypes.func.isRequired,
    handleOpenAddForm: PropTypes.func.isRequired,
    loading: PropTypes.bool.isRequired,
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
          <Button
            className={classes.headerBarButton}
            variant="fab"
            color="primary" 
            mini
            onClick={this.props.handleOpenAddForm}
          >
            <AddIcon />
          </Button>
        </Tooltip>

        {this.props.loading ? (
          <div className={classes.wrapper}>
            <Button className={classNames([classes.headerBarButton, classes.zoreRightMargin])} variant="fab" mini >
              <RefreshIcon />
            </Button>
            <CircularProgress size={40} className={classes.fabProgress} />
          </div>
        ) : (
          <Tooltip title="刷新页面">
            <Button className={classes.headerBarButton} variant="fab" mini onClick={this.props.handleRefresh}>
              <RefreshIcon />
            </Button>
          </Tooltip>
        )}
      </ContentHeaderBar>
    );
  }
}

export default withStyles(styles)(ListContentHeaderBar);
