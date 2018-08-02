import React from 'react';
import PropTypes from 'prop-types';
import { withStyles } from '@material-ui/core/styles';
import Grid from "@material-ui/core/Grid";
import Paper from '@material-ui/core/Paper';
import Toolbar from '@material-ui/core/Toolbar';
import Table from '@material-ui/core/Table';
import Button from '@material-ui/core/Button';
import Tooltip from '@material-ui/core/Tooltip';
import Breadcrumb from '../common/Breadcrumb';

// Icons
import RefreshIcon from "@material-ui/icons/RefreshRounded";
import AddIcon from "@material-ui/icons/AddRounded";

const styles = theme => {
  console.log(theme);
  return {
    root: {
      padding: theme.spacing.unit * 3
    },
    paper: {
      width: '100%',
    },
    header: {
      marginBottom: theme.spacing.unit * 2
    },
    title: {
      ...theme.typography.headline
    },
    headerRightButtons: {
      textAlign: 'right',
    },
    headerIconButton: {
      marginRight: theme.spacing.unit,
      '&:last-child': {
        marginRight: 0
      }
    }
  };
};

class TopicList extends React.Component {
  /**
   * The page prop types check.
   */
  static propTypes = {
    classes: PropTypes.object.isRequired,
  };

  /**
   * The page state.
   */
  static state = {
    topics: []
  };

  render() {
    const { classes } = this.props;

    return (
      <div className={classes.root}>
        <Grid className={classes.header} container spacing={8} alignItems="center">
          <Grid item xs>
            <Breadcrumb items={['动态', '话题']} />
            <h3 className={classes.title}>话题列表</h3>
          </Grid>
          <Grid item xs={2} className={classes.headerRightButtons}>
            <Tooltip title="Add a topic">
              <Button className={classes.headerIconButton} variant="fab" color="primary" aria-label="Add a topic" mini>
                <AddIcon />
              </Button>
            </Tooltip>

            <Tooltip title="Refresh page">
              <Button className={classes.headerIconButton} variant="fab" color="primary" aria-label="Refresh page" mini>
                <RefreshIcon />
              </Button>
            </Tooltip>
          </Grid>
        </Grid>
        <Paper className={classes.paper}>
          <Toolbar>
            <div>2</div>
          </Toolbar>
          <Table>Topic List.</Table>
        </Paper>
      </div>
    );
  }

  componentDidMount() {
    console.log(this.props);
  }
}

export default withStyles(styles)(TopicList);
