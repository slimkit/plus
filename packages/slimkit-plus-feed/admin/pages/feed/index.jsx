import React from 'react';
import PropTypes from 'prop-types';
import View from './index.view';
import * as api from '../../api/feed';

class Wrap extends React.Component {

  static propTypes = {
    location: PropTypes.object.isRequired,
  };

  state = {
    loading: false,
    cachedSearchParams: {},
    pagination: {
      total: 0,
      current: 1,
      limit: 5,
    },
    feeds: [],
    message: {
      type: 'info',
      text: '',
      open: false,
    }
  };

  componentDidUpdate(prevProps) {
    if (this.props.location !== prevProps.location) {
      window.scrollTo(0, 0);
    }
  }

  componentDidMount() {
    this.fetchFeeds({
      limit: this.state.pagination.limit,
      page: this.state.pagination.current,
    });
  }

  onChangePagination(pagination) {
    this.setState({
      pagination: {
        ...this.state.pagination,
        ...pagination,
      }
    });
  }

  showMessage(settings)
  {
    this.setState({message: {
      ...this.state.message,
      ...settings,
    }});
  }

  fetchFeeds(params) {
    let cachedSearchParams = {
      ...this.state.cachedSearchParams,
      limit: this.state.pagination.limit,
      pgae: this.state.pagination.current,
      ...params,
    };
    this.setState({ cachedSearchParams, loading: true, feeds: [] });
    api.list(cachedSearchParams)
      .then(({ data: { total, current_page: current, data } }) => {
        this.setState({
          pagination: {
            ...this.state.pagination,
            total,
            current,
          },
          feeds: data,
          loading: false,
        });
      })
      .catch(({ response: { data = '请求失败，请刷新页面重试！' } }) => {
        this.showMessage({
          type: 'error',
          open: true,
          text: data,
        });
        this.setState({
          loading: false,
        });
      })
    ;
  }

  onDestroy(feed) {
    api
      .destroy(feed.id)
      .then(() => {
        this.showMessage({
          open: true,
          type: 'success',
          text: '加入回收站成功'
        });
        this.fetchFeeds();
      })
      .catch(({ response: { data = '请求失败，请刷新页面重试！' } }) => {
        this.showMessage({
          type: 'error',
          open: true,
          text: data,
        });
      })
    ;
  }

  onRestore(feed) {
    api
      .restore(feed.id)
      .then(() => {
        this.showMessage({
          open: true,
          type: 'success',
          text: '移出回收站成功'
        });
        this.fetchFeeds();
      })
      .catch(({ response: { data = '请求失败，请刷新页面重试！' } }) => {
        this.showMessage({
          type: 'error',
          open: true,
          text: data,
        });
      })
    ;
  }

  render() {
    return (<View
      loading={this.state.loading}
      onFetch={this.fetchFeeds.bind(this)}
      feeds={this.state.feeds}
      pagination={this.state.pagination}
      onChangePagination={this.onChangePagination.bind(this)}
      message={{
        ...this.state.message,
        onClose: () => this.showMessage({ open: false }),
        onShow: (setting) => this.showMessage(setting),
      }}
      showMessage={this.showMessage.bind(this)}
      onDestroy={this.onDestroy.bind(this)}
      onRestore={this.onRestore.bind(this)}
    />);
  }
  
}

export default Wrap;
