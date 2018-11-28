import React from 'react';
import PropTypes from 'prop-types';
import classNames from 'classnames';
import ContentHeaderBar from '../../../components/common/ContentHeaderBar';
import Tooltip from '@material-ui/core/Tooltip';
import Button from '@material-ui/core/Button';
import CircularProgress from '@material-ui/core/CircularProgress';

// Icons
import RefreshIcon from '@material-ui/icons/RefreshRounded';

// styles
import { withStyles } from '@material-ui/core/styles';
import styles from './header-bar.style';

class HeaderBar extends React.Component
{
    static props = {
      loading: PropTypes.bool.isRequired,
      classes: PropTypes.object.isRequired,
      onRefresh: PropTypes.func.isRequired,
    }

    onRefresh()
    {
      this.props.onRefresh({});
    }

    render()
    {
      let { loading, classes } = this.props;

      return (
        <ContentHeaderBar
          title="动态列表"
          breadcrumbs={['动态']}
          rightGridXs={2}
        >
          {loading
            ? (
              <div className={classes.wrapper}>
                <Button className={classNames([classes.headerBarButton, classes.zoreRightMargin])} variant="fab" mini >
                  <RefreshIcon />
                </Button>
                <CircularProgress size={40} className={classes.fabProgress} />
              </div>
            )
            : (
              <Tooltip title="刷新页面">
                <Button
                  variant="fab"
                  mini
                  className={classes.headerBarButton}
                  onClick={this.onRefresh.bind(this)}
                >
                  <RefreshIcon />
                </Button>
              </Tooltip>
            )
          }
        </ContentHeaderBar>
      );
    }
}

export default withStyles(styles)(HeaderBar);
