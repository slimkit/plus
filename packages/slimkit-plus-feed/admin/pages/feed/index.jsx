import React from 'react';
import PropTypes from 'prop-types';

class FeedWrap extends React.Component
{
  static propTypes = {
    location: PropTypes.object.isRequired
  };

  componentDidUpdate(prevProps) {
    if (this.props.location !== prevProps.location) {
      window.scrollTo(0, 0);
    }
  }

  render() {
    return (
      <div>2222</div>
    );
  }
}

export default FeedWrap;
