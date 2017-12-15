import React, { Component, PropTypes } from 'react';
import { Table, TableBody, TableHeader, TableHeaderColumn, TableRow, TableRowColumn } from 'material-ui/Table';
import RaisedButton from 'material-ui/RaisedButton';
import Snackbar from 'material-ui/Snackbar';
import CircularProgress from 'material-ui/CircularProgress';
import request, { createRequestURI, queryString } from '../utils/request';

/**
 * Table settings.
 *
 * @type {Object}
 */
const tableState = {
  showCheckboxes: false,
  showRowHover: true,
  stripedRows: false,
};

/**
 * UI styles.
 *
 * @type {Object}
 */
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

class CommentsComponent extends Component {

  /**
   * Pros types.
   *
   * @type {Object}
   */
  static propTypes = {
    history: PropTypes.object.isRequired,
    location: PropTypes.object.isRequired,
  };

  /**
   * State data.
   *
   * @type {Object}
   */
  state = {
    /**
     * Comment data.
     *
     * @type {Array}
     */
    comments: [],

    /**
     * Page number.
     *
     * @type {Number}
     */
    page: 1,
    pervPage: null,
    nextPage: null,

    /**
     * Request data number.
     *
     * @type {Number}
     */
    limit: 20,

    /**
     * Snackbar options.
     *
     * @type {Object}
     */
    snackbar: {
      open: false,
      message: '',
    },

    /**
     * Delete IDs.
     *
     * @type {Array}
     */
    deleteIds: [],
  };

  /**
   * Render UI DOMs.
   *
   * @return {NODE}
   * @author Seven Du <shiweidu@outlook.com>
   */
  render() {
    return (
      <div>
        <Table>
          <TableHeader
            adjustForCheckbox={tableState.showCheckboxes}
            displaySelectAll={tableState.showCheckboxes}
          >
            <TableRow>
              <TableHeaderColumn>评论ID</TableHeaderColumn>
              <TableHeaderColumn>内容</TableHeaderColumn>
              <TableHeaderColumn>作者</TableHeaderColumn>
              <TableHeaderColumn>动态ID</TableHeaderColumn>
              <TableHeaderColumn>时间</TableHeaderColumn>
              <TableHeaderColumn>操作</TableHeaderColumn>
            </TableRow>
          </TableHeader>
          <TableBody
            displayRowCheckbox={tableState.showCheckboxes}
            showRowHover={tableState.showRowHover}
            stripedRows={tableState.stripedRows}
          >
            {this.state.comments.map(({
              comment_content: content,
              updated_at: updatedAt,
              feed_id: feedId,
              user: { name: username } = {},
              id
            }) => (
              <TableRow key={id}>
                <TableRowColumn>{ id }</TableRowColumn>
                <TableRowColumn>{ content }</TableRowColumn>
                <TableRowColumn>{ username }</TableRowColumn>
                <TableRowColumn>{ feedId }</TableRowColumn>
                <TableRowColumn>{ updatedAt }</TableRowColumn>
                <TableRowColumn>
                  {this.state.deleteIds.indexOf(id) !== -1 ? (
                    <CircularProgress size={20} />
                  ) : (
                    <span style={{...styles.actionStyle, ...styles.actionDelete}} onTouchTap={() => this.deleteComment(id)}>删除</span>
                  )}
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

  /**
   * Component did mount.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  componentDidMount() {
    this.requestComments();
  }

  /**
   * Get current page number.
   *
   * @return {Number}
   * @author Seven Du <shiweidu@outlook.com>
   */
  getCurrentPage() {
    const { search } = this.props.location;
    const { page = this.state.page } = queryString(search);

    return parseInt(page) || 1;
  }

  /**
   * Request server comment data.
   *
   * @param {Boolean|int} currentPage
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  requestComments(currentPage = false) {
    const { limit } = this.state;
    const page = currentPage || this.getCurrentPage();
    request.get(createRequestURI('comments'), {
      validateStatus: status => status === 200,
      params: { limit, page },
    }).then(({ data: { comments = [], pervPage = null, nextPage = null } }) => {
      this.setState({
        ...this.state,
        comments,
        pervPage,
        nextPage,
        page,
      });
    }).catch(() => this.setSnackbar({
      open: true,
      message: '加载失败，请重试。',
      autoHideDuration: 2500,
    }));
  }

  /**
   * Delete Comment.
   *
   * @param {Number} id comment ID.
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  deleteComment = id => {
    this.pushDeleteRrogress(id);
    request.delete(
      createRequestURI(`comments/${id}`),
      { validateStatus: status => status === 204 }
    ).then(() => {
      this.pullDeleteProgress(id);
      this.pullComment(id);
    }).catch(({ response: { data: { message = '删除失败' } = {} } = {} } = {}) => {
      this.pullDeleteProgress(id);
      this.setSnackbar({ open: true, autoHideDuration: 2500, message });
    });
  };

  /**
   * Pull comment.
   *
   * @param {Number} id comment ID.
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  pullComment = id => {
    let comments = [];
    this.state.comments.forEach(comment => {
      if (parseInt(id) !== parseInt(comment.id)) {
        comments.push(comment);
      }
    });

    this.setState({
      ...this.state,
      comments,
    });
  };

  /**
   * Push delete IDs.
   *
   * @param {int} id
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  pushDeleteRrogress = id => {
    this.setState({
      ...this.state,
      deleteIds: [...this.state.deleteIds, id],
    });
  };

  /**
   * Pull delete IDs.
   *
   * @param {int} id
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  pullDeleteProgress = id => {
    let deleteIds = [];
    this.state.deleteIds.forEach(commentId => {
      if (parseInt(id) !== parseInt(commentId)) {
        deleteIds.push(commentId);
      }
    });

    this.setState({
      ...this.state,
      deleteIds,
    });
  };

  /**
   * Page goto next.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  handleGoNextPage = () => {
    const { history: { push } } = this.props;
    const page = this.getCurrentPage() + 1;
    push(`/comments?page=${page}`);
    this.requestComments(page);
  };

  /**
   * Page goto perv.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
  handleGoPervPage = () => {
    const { history: { push } } = this.props;
    const page = this.getCurrentPage() - 1;
    push(`/comments?page=${page}`);
    this.requestComments(page);
  };

  /**
   * Setting snackbar options.
   *
   * @param {Object} snackbar
   * @return {vodi}
   * @author Seven Du <shiweidu@outlook.com>
   */
  setSnackbar = snackbar => {
    this.setState({
      ...this.state,
      snackbar: {
        onRequestClose: this.onSnackbarClose,
        ...snackbar,
      },
    });
  };

  /**
   * Close snackbar.
   *
   * @return {void}
   * @author Seven Du <shiweidu@outlook.com>
   */
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

export default CommentsComponent;
