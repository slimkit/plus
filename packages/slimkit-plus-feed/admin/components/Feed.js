/**
 * The file is admin feeds manage page.
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types';

import withStyles from '@material-ui/core/styles/withStyles';
import Grid from '@material-ui/core/Grid';
import Card from '@material-ui/core/Card';
import CardHeader from '@material-ui/core/CardHeader';
import CardContent from '@material-ui/core/CardContent';
import CardMedia from '@material-ui/core/CardMedia';
import CardActions from '@material-ui/core/CardActions';
import Typography from '@material-ui/core/Typography';
import Dialog from '@material-ui/core/Dialog';
import DialogContent from '@material-ui/core/DialogContent';
import DialogActions from '@material-ui/core/DialogActions';
import DialogTitle from '@material-ui/core/DialogTitle';
import DialogContentText from '@material-ui/core/DialogContentText';
import Snackbar from '@material-ui/core/Snackbar';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import IconButton from '@material-ui/core/IconButton';
import CircularProgress from '@material-ui/core/CircularProgress';
import Drawer from '@material-ui/core/Drawer';
import Chip from '@material-ui/core/Chip';
import Input from '@material-ui/core/Input';
import InputLabel from '@material-ui/core/InputLabel';
import MenuItem from '@material-ui/core/MenuItem';
import FormControl from '@material-ui/core/FormControl';
import { Link } from 'react-router-dom';

import FavoriteIcon from '@material-ui/icons/Favorite';
import ArrowUpward from '@material-ui/icons/ArrowUpward';
import Forum from '@material-ui/icons/Forum';
import Delete from '@material-ui/icons/Delete';
import CloseIcon from '@material-ui/icons/Close';
import _ from 'lodash';
import Select from '@material-ui/core/Select';
import TextField from '@material-ui/core/TextField';
import getQuery from '../utils/getQuery';
import { localDateToUTC, localDate } from '../utils/dateProcess';
import request, { createRequestURI } from '../utils/request';
import { showAmount } from '../utils/balance';

const styles = (theme) => ({
  root: {
    padding: theme.spacing.unit * 2,
    width: '100%',
    margin: 0
  },
  flexGrow: {
    flex: '1 1 auto'
  },
  drawer: {
    width: 450,
    overflowY: 'auto'
  },
  drawerImage: {
    width: '100%'
  },
  media: {
    position: 'relative'
  },
  drawerImageTitle: {
    width: `calc(100% - ${theme.spacing.unit * 4}px)`,
    position: 'absolute',
    bottom: 0,
    background: 'rgba(0, 0, 0, .4)',
    color: '#fff'
  },
  drawerRow: {
    display: 'flex',
    justifyContent: 'flex-start',
    flexWrap: 'wrap'
  },
  chip: {
    margin: theme.spacing.unit
  },
  loadMoreBtn: {
    margin: theme.spacing.unit,
    width: `calc(100% - ${theme.spacing.unit * 2}px)`
  },
  progress: {
    margin: `0 ${theme.spacing.unit}px`
  },
  progeessHide: {
    margin: `0 ${theme.spacing.unit}px`,
    visibility: 'hidden'
  },
  container: {
    display: 'flex',
    flexWrap: 'wrap',
    width: '100%',
    padding: theme.spacing.unit
  },
  formControl: {
    margin: theme.spacing.unit,
    minWidth: 120
  },
  title: {
    width: '100%',
    color: theme.palette.grey[500]
  },
  textField: {
    marginLeft: theme.spacing.unit,
    marginRight: theme.spacing.unit,
    width: 200
  },
  button: {
    margin: theme.spacing.unit
  },
  cursor: {
    cursor: 'pointer'
  },
  feedContent: {
    width: `calc(100% - ${theme.spacing.unit * 4}px)`,
    wordBreak: 'break-all'
  },
  amoutShow: {
    fontSize: '16px',
    padding: '8px',
    background: '#f4f5f5',
    marginBottom: '10px'
  },
  link: {
    cursor: 'pointer',
    textDecoration: 'none'
  }
});

class Feed extends Component {
  static propTypes = {
    classes: PropTypes.object.isRequired,
    location: PropTypes.object.isRequired,
  };

  state = {
    feeds: [],
    del: {
      feed: null,
      ing: false
    },
    snackbar: {
      open: false,
      message: '',
      vertical: 'bottom',
      horizontal: 'right'
    },
    deletePinned: {
      feed: null,
      ing: false
    },
    drawer: null,
    pinned: null,
    pinnedDay: 0,
    loadMoreBtnText: '加载更多',
    loadMoreBtnDisabled: false,
    loading: false,
    params: {
      type: 'all',
      pay: 'all',
      limit: 16,
      user_id: 0,
      from: 0,
      stime: '',
      etime: '',
      keyword: '',
      top: 'all',
      userName: '',
      needPay: false,
      current_page: 1,
      last_page: 1,
      total: 0
    },
    customer: false // 开启自定义时间对话框
  };

  render() {
    const { classes } = this.props;
    const {
      feeds = [],
      del,
      snackbar,
      drawer,
      params,
      customer,
      pinned,
      deletePinned
    } = this.state;

    return (
      <div>
        <Grid   container className={classes.root}>
          <div className={classes.container}>
            <form className={classes.container} autoComplete="off">
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>动态筛选</h5>
                <Input
                  placeholder="关键字"
                  aria-label="Description"
                  onChange={this.keyWordChanged}
                  value={params.keyword}
                />
              </FormControl>
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>用户ID</h5>
                <Input
                  placeholder="用户ID"
                  aria-label="Description"
                  type={'number'}
                  onChange={this.UserIdChanged}
                  value={params.user_id || ''}
                />
              </FormControl>
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>用户昵称</h5>
                <Input
                  placeholder="用户昵称"
                  aria-label="Description"
                  onChange={this.UserNameChanged}
                  value={params.userName}
                />
              </FormControl>
              <FormControl className={classes.formControl}>
                <InputLabel htmlFor="type">时间段</InputLabel>
                <Select
                  value={params.type}
                  onChange={this.handleTypeChanged('type')}
                >
                  <MenuItem value={'all'}>全部</MenuItem>
                  <MenuItem value={'today'}>今天</MenuItem>
                  <MenuItem value={'yesterday'}>昨天</MenuItem>
                  <MenuItem value={'week'}>最近一周</MenuItem>
                  <MenuItem value={'lastDay'}>截止昨天</MenuItem>
                  <MenuItem value={'customer'}>自定义时间</MenuItem>
                </Select>
              </FormControl>
              <FormControl className={classes.formControl}>
                <InputLabel htmlFor="from">来源</InputLabel>
                <Select
                  value={params.from}
                  onChange={this.handleTypeChanged('from')}
                >
                  <MenuItem value={0}>全部</MenuItem>
                  <MenuItem value={1}>PC</MenuItem>
                  <MenuItem value={2}>H5</MenuItem>
                  <MenuItem value={3}>苹果</MenuItem>
                  <MenuItem value={4}>安卓</MenuItem>
                  <MenuItem value={5}>其他</MenuItem>
                </Select>
              </FormControl>
              <FormControl className={classes.formControl}>
                <InputLabel htmlFor="from">是否付费</InputLabel>
                <Select
                  value={params.pay}
                  onChange={this.handleTypeChanged('pay')}
                >
                  <MenuItem value={'all'}>全部</MenuItem>
                  <MenuItem value={'free'}>免费</MenuItem>
                  <MenuItem value={'paid'}>付费</MenuItem>
                </Select>
              </FormControl>
              <FormControl className={classes.formControl}>
                <InputLabel htmlFor="top">是否置顶</InputLabel>
                <Select
                  value={params.top}
                  onChange={this.handleTypeChanged('top')}
                >
                  <MenuItem value={'all'}>全部</MenuItem>
                  <MenuItem value={'no'}>非置顶</MenuItem>
                  <MenuItem value={'yes'}>置顶</MenuItem>
                  <MenuItem value={'wait'}>待审核</MenuItem>
                  <MenuItem value={'reject'}>拒绝/过期</MenuItem>
                </Select>
              </FormControl>
              {params.user_id ? (
                <FormControl className={classes.formControl}>
                  <Chip
                    label={`用户: ${params.userName}`}
                    key={params.user_id}
                    onRequestDelete={this.handleRequestDelete}
                    className={classes.chip}
                  />
                </FormControl>
              ) : (
                ''
              )}

              <Button
                onClick={() => this.handleGetDatas()}
                color="primary"
                className={classes.button}
              >
                筛选
              </Button>
            </form>
          </div>
          {feeds.map(feed => (
            <Grid  item xs={12} sm={6} key={feed.id}>
              <Card>
                <CardHeader
                  className={classes.cursor}
                  avatar={
                    !feed.user.avatar ? (
                      <Avatar>{feed.user.name[0]}</Avatar>
                    ) : (
                      <Avatar src={feed.user.avatar} alt={feed.user.name} />
                    )
                  }
                  title={`${feed.user.name} (用户ID：${feed.user.id})`}
                  subheader={localDate(feed.created_at)}
                  onClick={() =>
                    this.getUserFeeds(feed.user.user_id, feed.user.name)
                  }
                />

                <CardContent
                  className={classes.feedContent}
                  onClick={() => this.handleRequestDrawer(feed.id)}
                >
                  <Typography>动态ID(#{feed.id})</Typography>
                  {feed.feed_content}
                </CardContent>

                <CardActions>
                  <Button disabled>
                    <FavoriteIcon />&nbsp;{feed.like_count}
                  </Button>

                  <Button disabled>
                    <Forum />&nbsp;{feed.feed_comment_count}
                  </Button>

                  <div className={classes.flexGrow} />

                  <IconButton  onClick={() => this.handlePushDelete(feed.id)}>
                    <Delete />
                  </IconButton>
                </CardActions>
                <CardActions>{this.renderPinnedDom(feed)}</CardActions>
              </Card>
            </Grid>
          ))}
        </Grid>
        <Button
          color="primary"
          className={classes.loadMoreBtn}
          onClick={() => this.handleLoadMoreFeed()}
          disabled={this.state.loadMoreBtnDisabled}
        >
          共[{this.state.params.total}]条动态，当前第[{
            this.state.params.current_page
          }]页/共[{this.state.params.last_page}]页 {this.state.loadMoreBtnText}
          <CircularProgress
            className={
              this.state.loading ? classes.progress : classes.progeessHide
            }
            color="primary"
            size={30}
          />
        </Button>
        <Dialog open={!!del.feed}>
          <DialogContent>确定要删除吗？</DialogContent>
          <DialogActions>
            {del.ing ? (
              <Button disabled>取消</Button>
            ) : (
              <Button  onClick={() => this.handlePushClose()}>取消</Button>
            )}
            {del.ing ? (
              <Button disabled>
                <CircularProgress size={14} />
              </Button>
            ) : (
              <Button color="primary"  onClick={() => this.handleDelete()}>
                删除
              </Button>
            )}
          </DialogActions>
        </Dialog>
        <Dialog open={customer}>
          <DialogTitle>{'自定义查询日期'}</DialogTitle>
          <DialogContent>
            <FormControl className={classes.formControl}>
              <TextField
                label="起始时间"
                step="300"
                type="date"
                defaultValue={params.stime}
                className={classes.textField}
                InputLabelProps={{
                  shrink: true
                }}
                onChange={this.handleStimeChanged}
              />
            </FormControl>
            <FormControl className={classes.formControl}>
              <TextField
                label="截止时间"
                step="300"
                defaultValue={params.etime}
                type="date"
                className={classes.textField}
                InputLabelProps={{
                  shrink: true
                }}
                onChange={this.handleEtimeChanged}
              />
            </FormControl>
          </DialogContent>
          <DialogActions>
            <Button
              onClick={() =>
                this.setState({
                  ...this.state,
                  customer: false,
                  params: {
                    ...params,
                    type: 'all'
                  }
                })
              }
              color="primary"
            >
              取消
            </Button>
            <Button onClick={this.handleChooseTime} color="primary">
              确定
            </Button>
          </DialogActions>
        </Dialog>
        <Snackbar
          anchorOrigin={{
            vertical: snackbar.vertical,
            horizontal: snackbar.horizontal
          }}
          open={!!snackbar.open}
          message={snackbar.message}
          autoHideDuration={3e3}
          onClose={() => this.handleSnackbarClose()}
          action={[
            <IconButton
              key="snackbar.close"
              color="inherit"
              onClick={() => this.handleSnackbarClose()}
            >
              <CloseIcon />
            </IconButton>
          ]}
        />

        <Drawer
          open={!!drawer}
          anchor="right"
          onClose={() => this.handleDrawerClose()}
        >
          {this.makeDrawerContent(drawer)}
        </Drawer>
        <Dialog open={!!pinned} onClose={() => this.handleAuditDialogColse()}>
          {this.doPinnedAudit(pinned) || ''}
        </Dialog>

        <Dialog open={deletePinned.feed !== null}>
          <DialogContent>确定要撤销置顶吗？</DialogContent>
          <DialogActions>
            {deletePinned.ing ? (
              <Button disabled>取消</Button>
            ) : (
              <Button  onClick={() => this.handleCloseDeletePinned()}>
                取消
              </Button>
            )}
            {deletePinned.ing ? (
              <Button disabled>
                <CircularProgress size={14} />
              </Button>
            ) : (
              <Button
                color="primary"
                onClick={() => this.handleDeletePinned(deletePinned.feed)}
              >
                撤销
              </Button>
            )}
          </DialogActions>
        </Dialog>
      </div>
    );
  }

  handleCloseDeletePinned() {
    this.setState({
      ...this.state,
      deletePinned: {
        ...this.state.deletePinned,
        ing: false,
        feed: null
      }
    });
  }

  // 检测关键字变化
  keyWordChanged = e => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        keyword: e.target.value
      }
    });
  };

  UserIdChanged = e => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        user_id: e.target.value
      }
    });
  };

  UserNameChanged = e => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        userName: e.target.value
      }
    });
  };

  getUserFeeds = (user, name = '') => {
    this.setState({ params: { ...this.state.params, userName: name, user_id: user } });

    this.handleGetDatas();
  };

  // 选择动态时间段
  handleTypeChanged = name => event => {
    const { params } = this.state;
    this.setState({
      ...this.state,
      customer:
        name === 'type' && event.target.value === 'customer' ? true : false,
      params: {
        ...params,
        [name]: event.target.value,
        stime:
          name === 'type' && event.target.value !== 'customer'
            ? ''
            : params.stime,
        etime:
          name === 'type' && event.target.value !== 'customer'
            ? ''
            : params.etime
      }
    });
  };

  // 删除用户选择
  handleRequestDelete = () => {
    this.setState({ params: { ...this.state.params, userName: '', user_id: 0 } });
    this.handleGetDatas();
  };

  handleStimeChanged = e => {
    const stime = e.target.value;
    this.setState({ params: { ...this.state.params, stime } });
  };
  handleEtimeChanged = e => {
    const etime = e.target.value;
    this.setState({ params: { ...this.state.params, etime } });
  };
  // 选择自定义时间
  handleChooseTime = () => {
    const { params: { stime = '', etime = '' } = {}, snackbar } = this.state;
    if (stime === '' && etime === '') {
      this.setState({ snackbar: { ...snackbar, open: true, message: '请至少选择一个时间' } });
    } else {
      this.setState({ customer: false });
    }
  };

  // 打开动态详情
  handleRequestDrawer(feed) {
    this.setState({ drawer: feed });
  }

  // 关闭动态详情
  handleDrawerClose() {
    this.setState({ drawer: null });
  }

  makeDrawerContent(feed_id = null) {
    if (!feed_id) {
      return null;
    }

    let feed = null;
    for (let index in this.state.feeds) {
      const item = this.state.feeds[index];
      if (parseInt(item.id) === parseInt(feed_id)) {
        feed = item;
        break;
      }
    }

    if (!feed) {
      return null;
    }

    const { classes } = this.props;
    const {
      user: { name, id: user_id },
      created_at,
      feed_content: content,
      images = [],
      paid_node,
      like_count: digg_count,
      feed_comment_count: comment_count
    } = feed;

    return (
      <Card elevation={0} className={classes.drawer}>
        <CardHeader
          avatar={<Avatar>{name[0]}</Avatar>}
          title={`${name} (${user_id})`}
          subheader={localDate(created_at)}
        />

        <CardContent className={classes.feedContent}>
          {paid_node && (
            <Typography component="span" className={classes.amoutShow}>
              文字收费：{paid_node.amount} 积分
            </Typography>
          )}
          {content}
        </CardContent>

        {images.map(({ id, paid_node }) => (
          <CardMedia key={id} src={createRequestURI(`files/${id}`)} className={classes.media}>
            <img
              src={createRequestURI(`files/${id}`)}
              className={classes.drawerImage}
            />
            {paid_node && (
              <CardHeader
                title={
                  (paid_node.extra === 'read' ? '查看' : '下载') +
                  '收费：' +
                  paid_node.amount / 100 +
                  ' 积分'
                }
                className={classes.drawerImageTitle}
              />
            )}
          </CardMedia>
        ))}

        <CardContent className={classes.drawerRow}>
          <Chip
            className={classes.chip}
            avatar={
              <Avatar>
                <FavoriteIcon />
              </Avatar>
            }
            label={digg_count}
          />
          <Link className={classes.link} to={`/comments?feed=${feed.id}`}>
            <Chip
              className={classes.chip}
              avatar={
                <Avatar>
                  <Forum />
                </Avatar>
              }
              label={comment_count}
            />
          </Link>
        </CardContent>
        <CardActions>
          <IconButton
            aria-label="置顶操作"
            onClick={() => this.handleOpenPinnedDialog(feed)}
          >
            <ArrowUpward />
          </IconButton>
        </CardActions>
      </Card>
    );
  }

  makeImages(images = []) {
    if (images.length >= 1) {
      const { id } = images.pop();

      return (
        <img src={createRequestURI(`files/${id}`)} />
      );
    }

    return null;
  }
  handleOpenPinnedDialog(feed) {
    this.setState({
      ...this.state,
      pinned: feed
    });
  }

  doPinnedAudit(feed = null) {
    if (!feed) {
      return null;
    }

    const {
      pinned: pin = null
    } = feed;

    if (
      !pin ||
      (pin && pin.expires_at && new Date(pin.expires_at) < new Date())
    ) {
      return (
        <section>
          <DialogTitle>{'动态置顶审核操作'}</DialogTitle>
          <DialogContent>
            <DialogContentText>
              该动态未置顶，或置顶时间已到，或已经被拒绝了申请，如果您要人工置顶，请在下方输入要置顶的天数
            </DialogContentText>
            <TextField
              autoFocus
              margin="dense"
              id="name"
              label="置顶天数"
              type="number"
              fullWidth
              onChange={this.handleDayChange}
            />
          </DialogContent>
          <DialogActions>
            <Button
              onClick={() => this.handleRequestClose()}
              color="primary"
            >
              取消
            </Button>
            <Button
              onClick={() => this.handleSetPinned(feed.id, pin ? pin.id : 0)}
              color="primary"
              autoFocus
            >
              确定
            </Button>
          </DialogActions>
        </section>
      );
    } else if (pin && pin.expires_at && new Date(pin.expires_at) > new Date()) {
      return (
        <section>
          <DialogTitle>{'动态置顶操作'}</DialogTitle>
          <DialogContent>
            <DialogContentText>
              动态作者: {feed.user.name}，{pin.amount !== 0
                ? `花费${showAmount(pin.amount)},申请该条动态置顶${pin.day}天`
                : `该条动态已由管理员置顶${pin.day}天`},到期时间{localDate(
                pin.expires_at
              )}, 是否需要撤销置顶?
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button
              onClick={() => this.handleRequestClose()}
              color="primary"
            >
              取消
            </Button>
            <Button
              onClick={() => this.handleDeletePinned(feed)}
              color="primary"
              autoFocus
            >
              撤销
            </Button>
          </DialogActions>
        </section>
      );
    } else {
      return (
        <section>
          <DialogTitle>{'动态置顶审核操作'}</DialogTitle>
          <DialogContent>
            <DialogContentText>
              动态作者: {feed.user.name} 花费 {showAmount(pin.amount)}，申请该条动态置顶{
                pin.day
              }天时间，是否同意？
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button
              onClick={() => this.handleRejectPinnedInDraw(feed)}
              color="primary"
            >
              拒绝
            </Button>
            <Button
              onClick={() => this.handleRequestClose()}
              color="primary"
            >
              取消
            </Button>
            <Button
              onClick={() => this.handleAuditPinned(feed)}
              color="primary"
              autoFocus
            >
              同意
            </Button>
          </DialogActions>
        </section>
      );
    }
  }

  handleAuditDialogColse() {
    this.setState({
      ...this.state,
      pinned: null
    });
  }

  handleSetPinned = (feed_id, pinned) => {
    const { pinnedDay: day = 0 } = this.state;
    if (day === 0) {
      return;
    }
    request
      .post(
        createRequestURI(`${feed_id}/pinned`),
        {
          pinned,
          day
        },
        {
          validateStatus: status => status === 201
        }
      )
      .then(({ data }) => {
        this.handleSnackbar({
          message: '设置成功!',
          open: true
        });
        let index = _.findIndex(this.state.feeds, { id: data.data.target });
        let state = this.state;
        state.feeds[index].pinned = {
          ...this.state.feeds[index].pinned,
          ...data.data
        };
        state.pinned = null;
        this.setState(state);
      })
      .catch(() => {
        this.handleSnackbar({
          message: '操作失败了!',
          open: true
        });
      });
  };

  handleGetPinned(feed) {
    const { pinned } = feed;

    return pinned;
  }

  renderPinnedDom(feed) {
    let pinned = this.handleGetPinned(feed);
    if (pinned === null) return '';
    const { expires_at = '', day = 0, amount } = feed.pinned;
    return expires_at ? (
      <Button
        color="primary"
        onClick={() => this.handleOpenDeleteDialog(feed)}
      >
        置顶到期时间{new Date(expires_at) < new Date() ? '[已过期]' : ''}:{' '}
        {localDate(expires_at)} | {showAmount(amount)}
      </Button>
    ) : (
      <Button
        color="primary"
        onClick={() => this.handleOpenPinnedDialog(feed)}
      >
        申请置顶：{day} 天, 积分 {showAmount(amount)}
      </Button>
    );
  }

  handleRejectPinnedInDraw(feed) {
    request
      .delete(createRequestURI(`feeds/pinneds/${feed.pinned.id}`), {
        validateStatus: status => status === 204
      })
      .then(() => {
        let state = this.state;
        state.pinned = null;
        let index = _.findIndex(this.state.feeds, { id: feed.id });
        state.feeds[index].pinned = null;
        this.handleSnackbar({
          message: '已拒绝申请',
          open: true
        });
        this.setState(state);
      });
  }

  handleOpenDeleteDialog(feed) {
    this.setState({
      ...this.state,
      pinned: feed
    });
  }

  handleDayChange = event => {
    this.setState({
      ...this.state,
      pinnedDay: event.target.value
    });
  };

  // 撤销动态置顶
  handleDeletePinned(feed) {
    this.setState({
      ...this.state,
      deletePinned: {
        ...this.state.deletePinned,
        ing: true
      }
    });
    request
      .delete(createRequestURI(`feeds/${feed.id}/pinned`), {
        validateStatus: status => status === 204
      })
      .then(() => {
        this.handleSnackbar({
          message: '已撤销!',
          open: true
        });
        this.handleCloseDeletePinned();

        let state = this.state;
        state.pinned = null;
        let index = _.findIndex(this.state.feeds, { id: feed.id });
        state.feeds[index].pinned = null;

        this.setState(state);
      })
      .catch(() => {
        this.handleSnackbar({
          message: '操作失败!',
          open: true
        });
        this.handleCloseDeletePinned();
      });
  }

  handleAuditPinned(feed) {
    request
      .patch(
        createRequestURI(`pinned/${feed.pinned.id}`),
        {
          ...{
            action: 'accept'
          }
        },
        {
          validateStatus: status => status === 201
        }
      )
      .then(({ data }) => {
        let index = _.findIndex(this.state.feeds, { id: feed.id });
        let state = this.state;
        state.feeds[index].pinned = data;
        this.handleSnackbar({
          message: '操作成功!',
          open: true
        });
        this.handleRequestClose();
        this.setState(state);
      })
      .catch(() => {
        this.handleSnackbar({
          message: '操作失败!',
          open: true
        });
      });
  }

  handleRequestClose() {
    this.setState({
      ...this.state,
      pinned: null
    });
  }

  handlePushDelete(feed) {
    const state = this.state;
    this.setState({
      ...state,
      del: { feed, ing: false }
    });
  }

  handlePushClose() {
    this.setState({
      ...this.state,
      del: { feed: null, ing: false }
    });
  }

  handleDelete() {
    const {
      del: { feed }
    } = this.state;
    this.setState({
      ...this.state,
      del: { feed, ing: true }
    });
    request
      .delete(createRequestURI(`feeds/${feed}`), {
        validateStatus: status => status === 204
      })
      .then(() => {
        this.handlePushClose();
        this.handlePullFeed(feed);
        this.handleSnackbar({
          message: '删除成功!',
          open: true
        });
      })
      .catch(
        ({
          response: {
            data: { message: [message = '删除失败，请检查网络！'] = [] } = {}
          } = {}
        } = {}) => {
          this.handlePushClose();
          this.handleSnackbar({
            message,
            open: true
          });
        }
      );
  }

  handlePullFeed(feed) {
    const state = this.state;
    let feeds = [];

    state.feeds.forEach(item => {
      if (parseInt(item.id) !== parseInt(feed)) {
        feeds.push(item);
      }
    });

    this.setState({ ...state, feeds });
  }

  handleSnackbar(snackbar = {}) {
    this.setState({
      ...this.state,
      snackbar: { ...this.state.snackbar, ...snackbar }
    });
  }

  handleSnackbarClose() {
    this.handleSnackbar({ open: false });
  }

  // 加载更多
  handleLoadMoreFeed() {
    const last = _.last(this.state.feeds);
    const { params } = this.state;

    if (parseInt(last.id) === 1) {
      this.setState({
        ...this.state,
        loadMoreBtnDisabled: true,
        loadMoreBtnText: '没有更多了',
        loading: false,
        snackbar: {
          ...this.state.snackbar,
          open: true,
          message: '没有更多了'
        }
      });
      return;
    }

    this.setState({
      ...this.state,
      loading: true,
      loadMoreBtnText: '',
      loadMoreBtnDisabled: true
    });

    request
      .get(createRequestURI('feeds'), {
        params: {
          ...params,
          page: params.current_page + 1
        }
      })
      .then(({ data }) => {
        let state = this.state;
        const {
          params: { limit }
        } = state;
        let feeds = state.feeds;
        feeds = [...feeds, ...data.data];
        let current_page = data.current_page;
        this.setState({
          ...state,
          ...{
            feeds,
            params: {
              ...this.state.params,
              current_page
            }
          }
        });
        if (data.data.length < limit || data.current_page === data.last_page) {
          this.setState({
            ...this.state,
            loading: false,
            loadMoreBtnText: '已加载全部',
            loadMoreBtnDisabled: true
          });
          return;
        }
        this.setState({
          ...this.state,
          loading: false,
          loadMoreBtnText: '加载更多',
          loadMoreBtnDisabled: false
        });
      });
  }

  // 获取动态数据
  handleGetDatas = () => {
    this.setState({
      ...this.state,
      loading: true,
      loadMoreBtnDisabled: true,
      loadMoreBtnText: '加载中...'
    });

    const { params } = this.state;

    request
      .get(
        createRequestURI('feeds'),
        {
          params: {
            ...params,
            stime: params.stime ? localDateToUTC(params.stime) : '',
            etime: params.etime ? localDateToUTC(params.etime) : '',
            page: 1
          }
        },
        { validateStatus: status => status === 200 }
      )
      .then(({ data }) => {
        let loadMoreBtnText = '加载更多',
          loadMoreBtnDisabled = false,
          loading = false;
        if (data.data.length < params.limit) {
          loadMoreBtnDisabled = true;
          loadMoreBtnText = '已加载全部';
        }
        this.setState({
          ...this.state,
          ...{
            feeds: data.data,
            params: {
              ...this.state.params,
              ...{
                current_page: data.current_page,
                last_page: data.last_page,
                total: data.total
              }
            },
            loading,
            loadMoreBtnDisabled,
            loadMoreBtnText
          }
        });
      });
  };

  componentDidMount() {
    const search = this.props.location.search;
    if (search) {
      this.setState({
        params: {
          ...this.state.params,
          ...getQuery(search)
        }
      });
    }
    this.handleGetDatas();
  }
}

export default withStyles(styles)(Feed);
