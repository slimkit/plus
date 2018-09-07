import React from 'react';
import lodash from 'lodash';
import View from './List.view';
import {
  list as listRequest,
  add  as addRequest,
  update as updateRequest,
  hotToggle as hotToggleRequest,
  destroy as destroyRequest,
  toggleReview as toggleReviewRequest
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
    submitting: false,
  }

  handleSearchBarToggle = () => {
    this.setState({ showSearchBar: !this.state.showSearchBar });
  }

  handleRequestTopics = (query = {}) => {
    if (this.state.loading) {
      return;
    }

    this.setState({ loading: true, topics: [] });
    listRequest({ params: { ...this.state.cacheRequestQuery, limit: this.state.limit, ...query } })
      .then(({ data }) => this.setState({
        loading: false,
        topics: data.data,
        page: data.current_page,
        total: data.total,
        cacheRequestQuery: { ...this.state.cacheRequestQuery, ...query}
      }))
      .catch(({ response: { data = { message: '数据加载失败，请刷新页面重拾！' } } = {} }) => this.setState({
        loading: false,
        message: {
          open: true,
          type: 'error',
          text: data
        },
        cacheRequestQuery: { ...this.state.cacheRequestQuery, ...query}
      }));
  }

  handleRefreshPage = () => this.handleRequestTopics()

  handleChangeLimit = event => {
    this.setState({ limit: event.target.value });
    this.handleRequestTopics({ page: 1, limit: event.target.value });
  }

  handleSubmitAddForm = (form, fn) => fn({
    submit: () => {
      this.setState({ submitting: true });
    
      return addRequest(form);
    },
    error: message => this.setState({
      message: { type: 'error', text: message, open: true },
      submitting: false,
    }),
    success: () => this.setState({
      submitting: false,
      message: { type: 'success', text: '添加成功!', open: true }
    })
  })

  handleSubmitEditForm = (id, form, fn) => fn({
    submit: () => {
      this.setState({ submitting: true });

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
        submitting: false,
        message: { open: true, text: '修改成功', type: 'success' },
        topics
      });
    },
    error: message => this.setState({
      submitting: false,
      message: { type: 'error', text: message, open: true }
    })
  })

  handleCloseMessage = () => this.setState({message: {
    ...this.state.message,
    open: false,
  }})

  handleToggleTopicHot = (id, fn) => fn({
    submit: () => {
      this.setState({ submitting: true });
      return hotToggleRequest(id);
    },
    success: () => this.setState({
      submitting: false,
      message: { type: 'success', text: '操作成功', open: true },
      topics: lodash.map(this.state.topics, (topic) => {
        if (parseInt(topic.id) === parseInt(id)) {
          topic.hot_at = topic.hot_at ? null : new Date();
        }

        return topic;
      })
    }),
    error: message => this.setState({
      submitting: false,
      message: { type: 'error', text: message, open: true }
    })
  });

  handleDestroyTopic = (id, fn) => fn({
    submit: () => {
      this.setState({ submitting: true });

      return destroyRequest(id);
    },
    success: () => this.setState({
      submitting: false,
      message: { type: 'success', text: '删除成功', open: true },
      topics: lodash.reduce(this.state.topics, (topics, topic) => {
        if (parseInt(topic.id) != parseInt(id)) {
          topics.push(topic);
        }

        return topics;
      }, [])
    }),
    error: message => this.setState({
      submitting: false,
      message: { type: 'error', text: message, open: true }
    })
  })

  handleToggleTopicReview = ({ id, status, successFn }) => {
    this.setState({ submitting: true });
    toggleReviewRequest(id, status)
      .then(() => {
        this.setState({
          submitting: false,
          message: { type: 'success', open: true, text: '切换成功' },
          topics: lodash.map(this.state.topics, (topic) => {
            if (parseInt(topic.id) === parseInt(id)) {
              return { ...topic, status };
            }

            return topic;
          })
        });
        successFn();
      })
      .catch(({ response: { data = { message: "操作失败" } } = {} }) => {
        this.setState({
          submitting: false,
          message: { type: 'error', open: true, text: data }
        });
      });
  }

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
      submitting={this.state.submitting}
      handleSubmitAddForm={this.handleSubmitAddForm}
      message={this.state.message}
      handleCloseMessage={this.handleCloseMessage}
      handleSubmitEditForm={this.handleSubmitEditForm}
      handleToggleTopicHot={this.handleToggleTopicHot}
      handleDestroyTopic={this.handleDestroyTopic}
      handleToggleTopicReview={this.handleToggleTopicReview}
    />;
  }

  componentDidMount() {
    this.handleRequestTopics();
  }
}

export default List;
