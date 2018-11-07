import React from 'react';
import PropTypes from 'prop-types';
import { localDate } from "../../utils/dateProcess";
import Paper from '@material-ui/core/Paper';
import Divider from '@material-ui/core/Divider';
import Table from '@material-ui/core/Table';
import TableHead from '@material-ui/core/TableHead';
import TableBody from '@material-ui/core/TableBody';
import TableRow from '@material-ui/core/TableRow';
import TableCell from '@material-ui/core/TableCell';
import TableFooter from '@material-ui/core/TableFooter';
import TablePagination from '@material-ui/core/TablePagination';
import Chip from '@material-ui/core/Chip';
import Button from '@material-ui/core/Button';
import Tooltip from '@material-ui/core/Tooltip';
import Snackbar from "../../components/common/Snackbar";

// styles
import withStyles from '@material-ui/core/styles/withStyles';
import styles from './index.style';

// Components
import HeaderBar from './modules/header-bar';
import SearchBar from './modules/search-bar';
import RenderPinnedButtom from './modules/render-pinned-buttom';
import Preview from './modules/preview';
import DisplayModal from './modules/pinned-modal';

// icons
import VisibilityIcon from '@material-ui/icons/Visibility';
import DeleteIcon from '@material-ui/icons/Delete';
import UndoIcon from '@material-ui/icons/Undo';
import CloseIcon from '@material-ui/icons/Close';
import FavoriteIcon from '@material-ui/icons/Favorite';

class View extends React.Component {

    static propTypes = {
      classes: PropTypes.object.isRequired,
      loading: PropTypes.bool.isRequired,
      onFetch: PropTypes.func.isRequired,
      feeds: PropTypes.array.isRequired,
      pagination: PropTypes.object.isRequired,
      onChangePagination: PropTypes.func.isRequired,
      message: PropTypes.object.isRequired,
      onDestroy: PropTypes.func.isRequired,
      onRestore: PropTypes.func.isRequired,
    };

    state = {
        preview: null,
        modal: {
            type: 'setting',
            payload: null,
        }
    };

    onChangeRowsPerPage(event)
    {
        let limit = parseInt(event.target.value);
        this.props.onChangePagination({
            limit,
            current: 1,
        });
        this.props.onFetch({
            limit,
            page: 1
        });
    }

    onChangePage(event, page)
    {
        this.props.onChangePagination({
            current: page + 1,
        });
        this.props.onFetch({
            page: page + 1,
        });
    }

    actionButtonBuilder = (feed, callbackName) => () => {
        this.props[callbackName](feed);
    }

    showPreview(feed) {
        this.setState({ preview: feed });
    }

    closePreview() {
        this.setState({ preview: null });
    }

    showModal(type, payload) {
        this.setState({ modal: { type, payload } });
    }

    closeModal() {
        this.setState({ modal: { ...this.state.modal, payload: null } });
    }

    render() {
      let { classes, loading, onFetch, feeds, pagination, message } = this.props;
      return (
        <div className={classes.root}>
          <HeaderBar loading={loading} onRefresh={onFetch} />
          <Paper>
            <SearchBar loading={loading} onSearch={onFetch} />
            <Divider /> 
            <Table>
                        
              {/* header */}
              <TableHead>
                <TableRow>
                  <TableCell>ID</TableCell>
                  <TableCell>发布者</TableCell>
                  <TableCell>话题</TableCell>
                  <TableCell>内容</TableCell>
                  <TableCell>发布时间</TableCell>
                  <TableCell className={classes.tableActions}>操作</TableCell>
                </TableRow>
              </TableHead>

              {/* Body */}
              <TableBody>
                  {feeds.map(feed => (
                      <TableRow key={feed.id}>
                        <TableCell component="th" scope="row">{ feed.id }</TableCell>
                        <TableCell>{ feed.user.name }</TableCell>
                        <TableCell>
                            { feed.topics ? (feed.topics.map(topic => (
                                <Chip
                                    key={topic.id}
                                    label={topic.name}
                                    className={classes.actionsFab}
                                />
                            ))) : null }
                        </TableCell>
                        <TableCell>{ feed.feed_content }</TableCell>
                        <TableCell>{ localDate(feed.created_at) }</TableCell>
                        <TableCell>

                            {/* 查看按钮 */}
                            <Tooltip title="查看">
                                <Button
                                    variant="fab"
                                    mini={true}
                                    className={classes.actionsFab}
                                    color="default"
                                    onClick={() => (this.showPreview(feed))}
                                >
                                    <VisibilityIcon />
                                </Button>
                            </Tooltip>

                            {/* 置顶 */}
                            <RenderPinnedButtom
                                className={classes.actionsFab}
                                feed={feed}
                                onAction={this.showModal.bind(this)}
                            />

                            {/* 删除 */}
                            {feed.deleted_at
                                ? (
                                    <Tooltip title="移出回收站">
                                        <Button
                                            variant="fab"
                                            mini={true}
                                            className={classes.actionsFab}
                                            color="secondary"
                                            onClick={this.actionButtonBuilder(feed, 'onRestore')}
                                        >
                                            <UndoIcon />
                                        </Button>
                                    </Tooltip>
                                )
                                : (
                                    <Tooltip title="加入回收站">
                                        <Button
                                            variant="fab"
                                            mini={true}
                                            className={classes.actionsFab}
                                            color="secondary"
                                            onClick={this.actionButtonBuilder(feed, 'onDestroy')}
                                        >
                                            <DeleteIcon />
                                        </Button>
                                    </Tooltip>
                                )
                            }

                        </TableCell>
                    </TableRow>
                  ))}
              </TableBody>

              {/* footer */}
              <TableFooter>
                    <TableRow>
                        <TablePagination
                            count={pagination.total}
                            page={pagination.current - 1}
                            rowsPerPage={pagination.limit}
                            labelRowsPerPage="每页条数："
                            labelDisplayedRows={({from, to, count}) => `${from} 至 ${to}，总：${count}`}
                            rowsPerPageOptions={[5, 10, 15, 20, 30]}
                            onChangeRowsPerPage={this.onChangeRowsPerPage.bind(this)}
                            onChangePage={this.onChangePage.bind(this)}
                        />
                    </TableRow>
                </TableFooter>

            </Table>
          </Paper>

          {/* Message */}
            <Snackbar
                open={message.open}
                message={message.text}
                type={message.type}
                onClose={message.onClose}
            />
            
            {/* 预览 */}
            <Preview
                feed={this.state.preview}
                classes={classes}
                onClose={this.closePreview.bind(this)}
            />

            {/* 弹窗 */}
            <DisplayModal
                {...this.state.modal}
                message={{
                    onClose: message.onClose,
                    onShow: message.onShow,
                }}
                onClose={this.closeModal.bind(this)}
                onRefresh={onFetch}
            />
        </div>
      );
    }
}

export default withStyles(styles)(View);
