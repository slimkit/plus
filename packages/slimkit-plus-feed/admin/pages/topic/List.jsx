import React from 'react';
import lodash from 'lodash';
import View from './List.view';
import {
  list as listRequest,
  add  as addRequest,
  update as updateRequest
} from '../../api/topic';

class List extends React.Component {

  state = {
    showSearchBar: false,
    loading: false,
    topics: [],
    message: {
      open: false,
      type: 'success',
      text: ''
    },
    cacheRequestQuery: {},
    page: 1,
    limit: 5,
    total: 0,
    addSubmitting: false,
  }

  handleSearchBarToggle = () => {
    this.setState({ showSearchBar: !this.state.showSearchBar });
  }

  handleRequestTopics = (query = {}) => {
    if (this.state.loading) {
      return;
    }

    this.setState({ loading: true, cacheRequestQuery: query, topics: [] });
    listRequest({ params: { ...this.state.cacheRequestQuery, limit: this.state.limit, ...query } })
      .then(({ data }) => this.setState({
        loading: false,
        topics: data.data,
        page: data.current_page,
        total: data.total
      }))
      .catch(({ response: { data = { message: '数据加载失败，请刷新页面重拾！' } } = {} }) => this.setState({
        loading: false,
        message: {
          open: true,
          type: 'error',
          text: data
        }
      }));
  }

  handleRefreshPage = () => this.handleRequestTopics()

  handleChangeLimit = event => {
    this.setState({ limit: event.target.value });
    this.handleRequestTopics({ page: 1, limit: event.target.value });
  }

  handleSubmitAddForm = (form, fn) => fn({
    submit: () => {
      this.setState({ addSubmitting: true });
    
      return addRequest(form);
    },
    error: message => this.setState({
      message: { type: 'error', text: message, open: true },
      addSubmitting: false,
    }),
    success: () => this.setState({
      addSubmitting: false,
      message: { type: 'success', text: '添加成功!', open: true }
    })
  })

  handleSubmitEditForm = (id, form, fn) => fn({
    submit: () => {
      this.setState({ addSubmitting: true });

      return updateRequest({ ...form, id });
    },
    success: () => {
      let topics = lodash.map(this.state.topics, (topic) => {
        if (parseInt(topic.id) === parseInt(id)) {
          return { ...topic, ...form };
        }

        return topic;
      });
      this.setState({
        addSubmitting: false,
        message: { open: true, text: '修改成功', type: 'success' },
        topics
      });
    },
    error: message => this.setState({
      addSubmitting: false,
      message: { type: 'error', text: message, open: true }
    })
  })

  handleCloseMessage = () => this.setState({message: {
    ...this.state.message,
    open: false,
  }})

  render() {
    return <View
      showSearchBar={this.state.showSearchBar}
      handleSearchBarToggle={this.handleSearchBarToggle}
      handleRefresh={this.handleRefreshPage}
      handleRequestTopics={this.handleRequestTopics}
      handleChangeLimit={this.handleChangeLimit}
      loading={this.state.loading}
      topics={this.state.topics}
      total={this.state.total}
      page={this.state.page}
      limit={this.state.limit}
      addSubmitting={this.state.addSubmitting}
      handleSubmitAddForm={this.handleSubmitAddForm}
      message={this.state.message}
      handleCloseMessage={this.handleCloseMessage}
      handleSubmitEditForm={this.handleSubmitEditForm}
    />;
  }

  componentDidMount() {
    this.handleRequestTopics();
  }
}

export default List;
