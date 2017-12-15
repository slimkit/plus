import React, { Component, PropTypes } from 'react';
import { Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn } from 'material-ui/Table';
import RaisedButton from 'material-ui/RaisedButton';
import Snackbar from 'material-ui/Snackbar';
import request, { createRequestURI, queryString } from '../utils/request';

const tableState = {
  showCheckboxes: false,
  showRowHover: true,
  stripedRows: false,
};

const styles = {
  actionStyle: {
    color: '#03a9f4',
    cursor: 'pointer',
  },
  actionDelete: {
    color: 'red',
  },
  pageButtonBox: {
    display: 'flex',
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
  },
  pageButton: {
    margin: 12
  }
};

class FeedsComponent extends Component {

  static propTypes = {
    history: PropTypes.object.isRequired,
    location: PropTypes.object.isRequired,
  };

  state = {
    feeds: [],
    page: 1,
    pervPage: null,
    nextPage: null,
    limit: 20,
    snackbar: {
      open: false,
      message: '',
    }
  };

  render() {
    // console.log(this.getCurrentPage());
    return (
      <div>
        <Table>
          <TableHeader
            adjustForCheckbox={tableState.showCheckboxes}
            displaySelectAll={tableState.showCheckboxes}
          >
            <TableRow>
              <TableHeaderColumn>ID</TableHeaderColumn>
              <TableHeaderColumn>内容</TableHeaderColumn>
              <TableHeaderColumn>作者</TableHeaderColumn>
              <TableHeaderColumn>统计</TableHeaderColumn>
              <TableHeaderColumn>审核</TableHeaderColumn>
              <TableHeaderColumn>操作</TableHeaderColumn>
            </TableRow>
          </TableHeader>
          <TableBody
            displayRowCheckbox={tableState.showCheckboxes}
            showRowHover={tableState.showRowHover}
            stripedRows={tableState.stripedRows}
          >
            {this.state.feeds.map(({ id, feed_title, feed_content, user = {}, ...feed }) => (
              <TableRow key={id}>
                <TableRowColumn>{ id }</TableRowColumn>
                <TableRowColumn>
                  {feed_title && (<h3>{ feed_title }</h3>)}
                  { feed_content }
                </TableRowColumn>
                <TableRowColumn>{ user.name }</TableRowColumn>
                <TableRowColumn>
                  <p>点赞：{ feed.feed_digg_count }</p>
                  <p>查看：{ feed.feed_view_count }</p>
                  <p>评论：{ feed.feed_comment_count }</p>
                </TableRowColumn>
                <TableRowColumn>{ this.getAuditStatus(feed.audit_status, id) }</TableRowColumn>
                <TableRowColumn>
                  <span style={{...styles.actionStyle, ...styles.actionDelete}} onTouchTap={() => this.deleteFeed(id)} >删除</span>
                </TableRowColumn>
              </TableRow>
            ))}
          </TableBody>
        </Table>
        <div style={styles.pageButtonBox}>
          {this.state.pervPage > 0 ? (<RaisedButton style={styles.pageButton} onTouchTap={this.handleGoPervPage} label="上一页" />) : (<div />)}
          {this.state.nextPage > 0 && (<RaisedButton style={styles.pageButton} onTouchTap={this.handleGoNextPage} label="下一页" />)}
        </div>
        <Snackbar {...this.state.snackbar} />
      </div>
    );
  }
  
  componentDidMount() {
    this.showFeeds();
  }

  getCurrentPage() {
    const { search } = this.props.location;
    const { page = this.state.page } = queryString(search);

    return parseInt(page) || 1;
  }

  getAuditStatus(status, feedId) {
    switch (parseInt(status)) {
      case 1:
        return '已审核';

      case 2:
        return '未通过';

      case 0:
      default:
        return (<div>
          <span style={styles.actionStyle} onTouchTap={() => this.reviewFeed(feedId, 1)} >通过</span>
          &nbsp;|&nbsp;
          <span style={styles.actionStyle} onTouchTap={() => this.reviewFeed(feedId, 2)} >不通过</span>
        </div>);
    }
  }

  showFeeds(currentPage = false) {
    const { limit } = this.state;
    const page = currentPage || this.getCurrentPage();
    request.get(
      createRequestURI('feeds'),
      {
        validateStatus: status => status === 200,
        params: {
          limit,
          page,
          show_user: 1,
        }
      }
    ).then(({ data: { feeds = [], nextPage = null, pervPage = null } }) => {
      this.setState({
        ...this.state,
        feeds,
        nextPage,
        pervPage,
        page,
      });
    });
  }

  handleGoNextPage = () => {
    const { history: { push } } = this.props;
    const page = this.getCurrentPage() + 1;
    push(`/feeds?page=${page}`);
    this.showFeeds(page);
  };

  handleGoPervPage = () => {
    const { history: { push } } = this.props;
    const page = this.getCurrentPage() - 1;
    push(`/feeds?page=${page}`);
    this.showFeeds(page);
  };

  reviewFeed = (feedId, audit) => {
    request.patch(
      createRequestURI(`feeds/${feedId}/review`),
      { audit },
      { validateStatus: status => status === 201 }
    ).then(({ data: { message = '操作成功' } }) => {
      this.updateFeed(feedId, { audit_status: audit });
      this.setSnackbar({
        open: true,
        autoHideDuration: 2500,
        message,
      });
    }).catch(({ response: { data: { message = '操作失败' } = {} } = {} }) => {
      this.setSnackbar({
        open: true,
        autoHideDuration: 2500,
        message,
      });
    });
  };

  deleteFeed = feedId => {
    request.delete(
      createRequestURI(`feeds/${feedId}`),
      { validateStatus: status => status === 204 }
    ).then(() => {
      this.deleteFeedInState(feedId);
      this.setSnackbar({
        open: true,
        message: '删除成功',
        autoHideDuration: 2500,
      });
    }).catch(({ response: { data: { message = '删除失败' } = {} } = {} }) => {
      this.setSnackbar({
        open: true,
        autoHideDuration: 2500,
        message,
      });
    });
  };

  deleteFeedInState = feedId => {
    let feeds = [];

    this.state.feeds.forEach(feed => {
      if (feed.id !== feedId) {
        feeds.push(feed);
      }
    });

    this.setState({
      ...this.state,
      feeds,
    });
  };

  updateFeed = (feedId, data) => {
    let feeds = [];
    this.state.feeds.forEach(feed => {
      if (feed.id === feedId) {
        feeds.push({
          ...feed,
          ...data
        });

        return ;
      }

      feeds.push(feed);
    });

    this.setState({
      ...this.state,
      feeds,
    });
  };

  setSnackbar = snackbar => {
    this.setState({
      ...this.state,
      snackbar: {
        onRequestClose: this.onSnackbarClose,
        ...snackbar,
      },
    });
  };

  onSnackbarClose = () => {
    this.setState({
      ...this.state,
      snackbar: {
        open: false,
        message: '',
      }
    });
  };
}

export default FeedsComponent;
