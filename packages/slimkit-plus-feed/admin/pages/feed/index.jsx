import React from 'react';
import PropTypes from 'prop-types';
import withStyles from '@material-ui/core/styles/withStyles';
import styles from './index.style';

export default withStyles(styles)(
  class Wrap extends React.Component {
    static propTypes = {
      location: PropTypes.object.isRequired,
      classes: PropTypes.object.isRequired,
    };

    componentDidUpdate(prevProps) {
      if (this.props.location !== prevProps.location) {
        window.scrollTo(0, 0);
      }
    }
  
    render() {
      console.log(this.props);
      return (
        <div>2222</div>
      );
    }
    
  }
);
