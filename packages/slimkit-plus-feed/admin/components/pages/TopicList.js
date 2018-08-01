import React from 'react';

class TopicList extends React.Component {
  /**
   * The page state.
   */
  static state = {
    topics: []
  };

  render() {
    return (
      <div>Topic List.</div>
    );
  }

  componentDidMount() {
    console.log(this.props);
  }
}

export default TopicList;
