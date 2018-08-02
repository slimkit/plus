import React from 'react';
import View from './List.view';
import { list as listRequest } from '../../api/topic';

class List extends React.Component {

  state = {
    showSearchBar: false,
    loading: true,
    topics: [],
    error: null
  }

  handleSearchBarToggle = () => {
    this.setState({ showSearchBar: !this.state.showSearchBar });
  }

  handleRequestTopics = (query = {}) => {
    this.setState({ loading: true });
    listRequest({ params: query })
      .then(({ data }) => this.setState({
        loading: false,
        topics: data.data
      }))
      .catch(({ response: { data = { message: '数据加载失败，请刷新页面重拾！' } } = {} }) => this.setState({
        loading: false,
        error: data
      }));
  }

  render() {
    return <View
      showSearchBar={this.state.showSearchBar}
      handleSearchBarToggle={this.handleSearchBarToggle}
      loading={this.state.loading}
      topics={this.state.topics}
    />;
  }

  componentDidMount() {
    this.handleRequestTopics();
  }
}

export default List;
