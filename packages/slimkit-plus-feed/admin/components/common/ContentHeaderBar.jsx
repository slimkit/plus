import React from 'react';
import PropTypes from 'prop-types';
import { withStyles } from '@material-ui/core/styles';
import Grid from '@material-ui/core/Grid';
import Breadcrumb from './Breadcrumb';
import styles from './ContentHeaderBar.styles';

class ContentHeaderBar extends React.Component {
    static propTypes = {
      classes: PropTypes.object.isRequired,
      title: PropTypes.string.isRequired,
      breadcrumbs: PropTypes.array,
      rightGridXs: PropTypes.number,
      className: PropTypes.string,
      children: PropTypes.node
    };

    static defaultProps = {
      breadcrumbs: [],
      rightGridXs: 2,
      className: ''
    };

    render() {
      let { classes, title, breadcrumbs, className, rightGridXs, children, ...props } = this.props;
      return (
        <Grid
          container
          alignItems="center"
          spacing={8}
          className={`${classes.root} ${className}`}
          {...props}
        >
          <Grid item xs>
            <Breadcrumb items={breadcrumbs} />
            <h3 className={classes.title}>{ title }</h3>
          </Grid>
          <Grid item xs={rightGridXs} className={classes.headerRightButtons}>
            { children }
          </Grid>
        </Grid>
      );
    }
}

export default withStyles(styles)(ContentHeaderBar);
