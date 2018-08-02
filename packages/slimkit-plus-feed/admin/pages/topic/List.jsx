import React from 'react';
import View from './List.view';

class List extends React.Component {

  state = {
    showSearchBar: false,
    topics: []
  }

  handleSearchBarToggle = () => {
    this.setState({ showSearchBar: !this.state.showSearchBar });
  }

  render() {
    return <View
      showSearchBar={this.state.showSearchBar}
      handleSearchBarToggle={this.handleSearchBarToggle}
    />;
  }
}

export default List;
