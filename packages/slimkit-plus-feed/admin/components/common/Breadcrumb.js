import React from 'react';
import PropTypes from 'prop-types';
import { withStyles } from '@material-ui/core/styles';

const styles = theme => {
  return {
    root: {
      ...theme.typography.subheading
    },
    item: {
      color: theme.palette.text.secondary,
      display: 'inline-block',
      '&:last-child:after': {
        display: 'none'
      },
      '&:after': {
        display: 'inline-block',
        float: 'initial',
        content: '"/"',
        paddingLeft: theme.spacing.unit,
        paddingRight: theme.spacing.unit,
        color: theme.palette.text.disabled
      },
    }
  };
};

class Breadcrumb extends React.Component {

    static propTypes = {
      classes: PropTypes.object.isRequired,
      items: PropTypes.array.isRequired,
      className: PropTypes.string
    };

    static defaultProps = {
      className: ''
    };

    render() {
      let { className, classes, items } = this.props;
      return (
        <div className={`${classes.root} ${className}`}>
          { items.map(item => (
            <span key={item} className={classes.item}>{ item }</span>
          )) }
        </div>
      );
    }
}

export default withStyles(styles)(Breadcrumb);
