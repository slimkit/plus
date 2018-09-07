/**
 * The file is admin feeds comments manage page.
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
    padding: theme.spacing.unit,
    margin: 0,
    width: '100%'
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
    background: 'rgba(255, 255, 255, .4)',
    color: '#fff'
  },
  drawerRow: {
    display: 'flex',
    justifyContent: 'flex-start',
    flexWrap: 'wrap'
  },
  chip: {
    margin: theme.spacing.unit,
  },
  loadMoreBtn: {
    margin: theme.spacing.unit,
    width: `calc(100% - ${theme.spacing.unit * 2 }px)`
  },
  progress: {
    margin: `0 ${theme.spacing.unit}px`,
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
    minWidth: 120,
  },
  textField: {
    marginLeft: theme.spacing.unit,
    marginRight: theme.spacing.unit,
    width: 200,
  },
  button: {
    margin: theme.spacing.unit,
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
});

class Comment extends Component
{
  static propTypes = {
    classes: PropTypes.object.isRequired,
    location: PropTypes.object.isRequired,
  };

  state = {
    comments: [],
    pinneds: [],
    del: {
      comment: null,
      ing: false,
    },
    pinnedDialog: {
      comment: null,
      ing: false
    },
    pinned: null,
    snackbar: {
      open: false,
      message: '',
      vertical: 'bottom',
      horizontal: 'right',
    },
    drawer: null,
    loadMoreBtnText: '加载更多',
    loadMoreBtnDisabled: false, // 加载按钮是否禁用
    loading: false, // 正在加载
    nextPage: null, // 下一页
    currentPage: 1,
    lastPage: 1,
    total: 0,
    pinnedDay: 0,
    params: {
      type: 'all', // 时间段
      stime: '', // 自定义起始时间
      etime: '', // 自定义结束时间
      top: 'all', // 是否置顶
      user_id: 0, // 某个用户发布的评论
      userName: '', // 用户名称
      keyword: '', // 关键字
      limit: 16, // 每页条数
      pinned_stime: '', // 置顶时间段起始时间
      pinned_etime: '', // 置顶时间段结束时间
      pinned_type: 'all', // 置顶类型 过期/未过期/自定义事件筛选
      feed: 0, // 所属动态id
    },
    customer: false,
    pinned_customer: false,
    feed: {
      id: 0
    }
  };

  // 删除评论
  handleDelete () {
    const { del: { comment = null } } = this.state;

    this.setState({
      del: {comment: comment, ing: true}
    });

    request.delete(
      createRequestURI(`comments/${comment}`), {
        validateStatus: status => status === 204
      }
    ).then(() => {
      let { comments = [] } = this.state;
      const index = _.findIndex(comments, com => {
        return comment === com.id;
      });
      comments.splice(index, 1);

      this.setState({
        ...this.state,
        comments: comments,
        del: { comment: null, ing: false }
      });
    });
  }

  handlePushDelete (id) {
    this.setState({
      ...this.state,
      del: { comment: id, ing: false }
    });
  }

  handlePushClose () {
    this.setState({
      ...this.state,
      del: { comment: null, ing: false }
    });
  }

  handleLoadMoreComments () {
    const { nextPage = null, comments, pinneds, params } = this.state;
    if (!nextPage) {
      return false;
    }

    this.setState({
      loading: true,
      loadMoreBtnText: '',
      loadMoreBtnDisabled: true
    });
    request.get(
      createRequestURI('comments') ,{
        params: {
          ...params,
          page: nextPage
        }
      },
      {
        validateStatus: status => status === 200
      }
    ).then(({ data = [] }) => {
      let loadMoreBtnText, loadMoreBtnDisabled, loading = false;
      if (!data.nextPage) {
        loadMoreBtnText = '已全部加载  ';
        loadMoreBtnDisabled = true;
      } else {
        loadMoreBtnText = '加载更多';
        loadMoreBtnDisabled = false;
      }
      this.setState({
        ...this.state,
        comments: [...comments, ...data.comments],
        pinneds: [...pinneds, ...data.pinneds],
        loading,
        loadMoreBtnDisabled,
        loadMoreBtnText,
        nextPage: data.nextPage
      });
    });
  }

  // 检测关键字变化
  keyWordChanged = (e) => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        keyword: e.target.value
      }
    });
  };

  handleStimeChanged = (e) => {
    const stime = e.target.value;
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        stime: stime
      }
    });
  }
  handleSnackbar(snackbar = {}) {
    this.setState({
      ...this.state,
      snackbar: { ...this.state.snackbar, ...snackbar }
    });
  }

  handleEtimeChanged = (e) => {
    const etime = e.target.value;
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        etime: etime
      }
    });
  };

  // 选择动态时间段
  handleTypeChanged = name => event => {
    let value = event.target.value;
    const { params } = this.state;
    let pinned_etime = params.pinned_etime, 
      pinned_stime = params.pinned_stime,
      pinned_type = params.pinned_type;

    if (name === 'top') {
      pinned_stime = '';
      pinned_etime = '';
      pinned_type = 'all';
    }

    if(name === 'pinned_type') {
      pinned_type = value;
      if( event.target.value != 'customer' ) {
        pinned_etime = '';
        pinned_stime = '';
      }
    }

    this.setState({
      ...this.state,
      customer: (name === 'type' && value === 'customer') ? true : false,
      pinned_customer: (name === 'pinned_type' && value === 'customer') ? true : false,
      params: {
        ...params,
        [name]: value,
        pinned_etime: pinned_etime,
        pinned_stime: pinned_stime,
        pinned_type: pinned_type
      }
    });
  };

  handleStatusChanged = name => e => {
    let value = e.target.value;
    this.setState({
      ...this.state,
      [name]: value
    });
  };

  handlePinnedEtimeChanged = (e) => {
    const etime = e.target.value;
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        pinned_etime: etime
      }
    });
  };

  handlePinnedStimeChanged = (e) => {
    const etime = e.target.value;
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        pinned_stime: etime
      }
    });
  };

  // 选择自定义时间
  handleChooseTime = () => {
    const { params: { stime = '', etime = '' } = {} , snackbar} = this.state;
    if(stime === '' && etime === '') {
      this.setState({
        ...this.state,
        snackbar: {
          ...snackbar,
          open: true,
          message: '请至少选择一个时间'
        }
      });
    } else {
      this.setState({
        ...this.state,
        customer: false
      });
    }
  };

  // 选择动态筛选的自定义时间
  handleTopChooseTime = () => {
    const { params: { pinned_stime = '', pinned_etime = '' } = {} , snackbar} = this.state;
    if(pinned_stime === '' && pinned_etime === '') {
      this.setState({
        ...this.state,
        snackbar: {
          ...snackbar,
          open: true,
          message: '请至少选择一个时间'
        }
      });
    } else {
      this.setState({
        ...this.state,
        pinned_customer: false
      });
    }
  };

  // 删除用户选择
  handleRequestDelete = () => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        userName: '',
        user_id: 0,
      }
    });

    this.handleGetDatas();
  };

  // 获取用户发布的评论
  getUserComments = (user, name = '') => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        userName: name,
        user_id: user,
      }
    });

    this.handleGetDatas();
  };

  // 获取评论的置顶数据
  handleGetPinned (comment_id) {

    return _.reduce(this.state.pinneds, (defaultValue, value) => {
      if(value.target === comment_id) {
        defaultValue = value;
      }

      return defaultValue;
    }, null);
  }

  // 打开一个置顶操作框
  handlePinnedOpen (comment) {
    this.setState({
      ...this.state,
      pinned: comment
    });
  }

  handleRejectOpen(comment)
  {
    this.setState({
      ...this.state,
      pinnedDialog: {
        comment,
        ing: false
      }
    });
  }

  feedIdChange = e => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        feed: e.target.value
      }
    });
  };

  userUserChange = e => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        user_id: e.target.value
      }
    });
  };

  userNameChange = e => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        userName: e.target.value
      }
    });
  };

  renderPinnedDom(comment) {
    let pinned = this.handleGetPinned(comment.id);

    if(pinned === null) return '';

    const { expires_at = '', day = 0, amount } = pinned;
    return (
      expires_at 
        ?
        <Button 
          color="secondary"
          onClick={ () => this.handlePinnedOpen(comment) }
        >
            置顶到期时间{ new Date(expires_at) < new Date() ? '[已过期]' : ''}: {localDate(expires_at)} | {showAmount(amount)}
        </Button>
        :
        <Button 
          color="secondary"
          onClick={ () => this.handlePinnedOpen(comment) }
        >
            申请置顶：{day} 天, 费用 {showAmount(amount)}
        </Button>
    );
  }

  render () {
    const { classes } = this.props;
    const { drawer, feed, comments = [], del, snackbar, params, customer, pinned_customer, pinned } = this.state;

    return(
      <div>
        <Grid  container className={classes.root}>
          <div className={classes.container}>
            <form className={classes.container} autoComplete="off">
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>评论筛选</h5>
                <Input
                  placeholder="关键字"
                  aria-label="Description"
                  onChange={this.keyWordChanged}
                />
              </FormControl>
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>所属动态ID</h5>
                <Input
                  placeholder="所属动态ID"
                  aria-label="Description"
                  onChange={this.feedIdChange}
                  value={this.state.params.feed || ''}
                />
              </FormControl>
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>用户ID</h5>
                <Input
                  placeholder="用户ID"
                  aria-label="Description"
                  onChange={this.userUserChange}
                  value={this.state.params.user_id || ''}
                />
              </FormControl>
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>用户名</h5>
                <Input
                  placeholder="用户名"
                  aria-label="Description"
                  onChange={this.userNameChange}
                  value={this.state.params.userName}
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
                <InputLabel htmlFor="top">是否置顶</InputLabel>
                <Select
                  value={params.top}
                  onChange={this.handleTypeChanged('top')}
                >
                  <MenuItem value={'all'}>
                    全部
                  </MenuItem>
                  <MenuItem value={'no'}>非置顶</MenuItem>
                  <MenuItem value={'yes'}>置顶</MenuItem>
                </Select>
              </FormControl>
              {
                (params.top === 'yes') ?
                  <FormControl className={classes.formControl}>
                    <InputLabel htmlFor="pinned_type">是否过期</InputLabel>
                    <Select
                      value={params.pinned_type}
                      onChange={this.handleTypeChanged('pinned_type')}
                    >
                      <MenuItem value={'all'}>
                        全部
                      </MenuItem>
                      <MenuItem value={'no'}>已过期</MenuItem>
                      <MenuItem value={'yes'}>未过期</MenuItem>
                      <MenuItem value="customer">自定义时间</MenuItem>
                    </Select>
                  </FormControl>
                  :
                  ''
              }
              {
                params.user_id ?
                  <FormControl className={classes.formControl}> 
                    <Chip
                      label={`用户: ${params.userName}`}
                      key={params.user_id}
                      onRequestDelete={this.handleRequestDelete}
                      className={classes.chip}
                    />
                  </FormControl>
                  :
                  ''
              }
              
              <Button variant="contained" onClick={ () => this.handleGetDatas() } color="primary" className={classes.button}>
                筛选
              </Button>
            </form>
          </div>
          { comments.map(
            comment => (

              <Grid  item xs={12} sm={6} key={comment.id}>
                <Card >

                  <CardHeader
                    className={classes.cursor}
                    onClick={() => this.getUserComments(comment.user_id, comment.user.name)}
                    avatar={<Avatar>{name[0]}</Avatar>}
                    title={`${comment.user.name} (${comment.user_id})`}
                    subheader={localDate(comment.created_at)}
                  />

                  <CardContent
                  >
                    <Typography>
                    #{comment.id} <Button color="primary" onClick={ () => this.handleShowFeed (comment.commentable_id)}>查看动态</Button>
                    </Typography>
                  评论内容: {comment.body}
                  </CardContent>

                  <CardActions>
                    <IconButton
                      onClick={() => this.handlePinnedOpen(comment)}
                    >
                      <ArrowUpward />
                    </IconButton>
                    <div className={classes.flexGrow} />

                    <IconButton
                      onClick={() => this.handlePushDelete(comment.id)}
                    >
                      <Delete />
                    </IconButton>

                  </CardActions>
                  <CardActions>
                    {this.renderPinnedDom(comment)}
                  </CardActions>

                </Card>
              </Grid>
            ))}
        </Grid>
        <Button
          variant="contained"
          color="primary"
          className={classes.loadMoreBtn}
          onClick={() => this.handleLoadMoreComments()}
          disabled={this.state.loadMoreBtnDisabled}
        >
          共[{this.state.total}]条评论，当前第[{this.state.currentPage}]页/共[{this.state.lastPage}]页 {this.state.loadMoreBtnText}
          <CircularProgress
            className={this.state.loading ? classes.progress : classes.progeessHide}
            color="secondary"
            size={30}
          />
        </Button>
        <Dialog open={ customer } >
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
                  shrink: true,
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
                  shrink: true,
                }}
                onChange={this.handleEtimeChanged}
              />
            </FormControl>
          </DialogContent>
          <DialogActions>
            <Button 
              onClick={
                () => this.setState({
                  ...this.state,
                  customer: false,
                  params: { ...params,
                    type: 'all'
                  }
                }
                )} 
              color="primary"
            >
              取消
            </Button>
            <Button onClick={this.handleChooseTime} color="primary">
              确定
            </Button>
          </DialogActions>
        </Dialog>
        <Dialog open={ pinned_customer } >
          <DialogTitle>{'自定义置顶查询日期'}</DialogTitle>
          <DialogContent>
            <FormControl className={classes.formControl}>
              <TextField
                label="起始时间"
                step="300"
                type="date"
                defaultValue={params.pinned_stime}
                className={classes.textField}
                InputLabelProps={{
                  shrink: true,
                }}
                onChange={this.handlePinnedStimeChanged}
              />
            </FormControl>
            <FormControl className={classes.formControl}>
              <TextField
                label="截止时间"
                step="300"
                defaultValue={params.pinned_etime}
                type="date"
                className={classes.textField}
                InputLabelProps={{
                  shrink: true,
                }}
                onChange={this.handlePinnedEtimeChanged}
              />
            </FormControl>
          </DialogContent>
          <DialogActions>
            <Button 
              onClick={
                () => this.setState({
                  ...this.state,
                  pinned_customer: false,
                  params: { ...params,
                    pinned_type: 'all'
                  }
                }
                )} 
              color="primary"
            >
              取消
            </Button>
            <Button onClick={this.handleTopChooseTime} color="primary">
              确定
            </Button>
          </DialogActions>
        </Dialog>
        <Dialog open={!! del.comment}>
          <DialogContent>确定要删除此条评论？</DialogContent>
          <DialogActions>
            { del.ing
              ? <Button disabled>取消</Button>
              : <Button  onClick={() => this.handlePushClose()}>取消</Button>
            }
            { del.ing
              ? <Button disabled><CircularProgress size={14} /></Button>
              : <Button color="primary"  onClick={() => this.handleDelete()}>删除</Button>
            }
          </DialogActions>
        </Dialog>
        <Snackbar
          anchorOrigin={{ vertical: snackbar.vertical, horizontal: snackbar.horizontal }}
          open={!! snackbar.open}
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
          open={!! drawer}
          anchor="right"
          onClose={() => this.handleDrawerClose()}
        >
          {this.makeDrawerContent(feed)}
        </Drawer>

        <Dialog open={!! pinned} onClose={ () => this.handleRequestClose()}>
          {this.doPinnedAudit(pinned) || ''}
        </Dialog>
      </div>
    );
  }
  /**
   * 关闭撤销置顶确认窗口
   */
  handleCloseRejectClose ()
  {
    this.setState({
      ...this.state,
      pinnedDialog: {
        comment: null,
        ing: false
      }
    });
  }

  handleSnackbarClose () {
    this.handleSnackbar({ open: false });
  }
  // 关闭动态详情
  handleDrawerClose () {
    this.setState({
      ...this.state,
      drawer: null,
    });
  }

  handleShowFeed = (feed) => {
    if (feed === this.state.feed.id) {
      this.setState({
        ...this.state,
        drawer: true
      });
      return;
    }

    request.get(
      createRequestURI(`feeds/${feed}`),
      {
        validateStatus: status => status === 200
      }
    ).then(({ data = {}}) => {
      this.setState({
        ...this.state,
        drawer: true,
        feed: { ...data }
      });
    });
  };

  makeDrawerContent(feed) {
    if (! _.keys(feed).length || feed.id === 0) {
      return null;
    }

    const { classes } = this.props;
    const {
      id: feed_id,
      user: {
        name,
        id: user_id
      },
      created_at,
      feed_content: content,
      images = [],
      paid_node,
      feed_digg_count: digg_count,
      feed_comment_count: comment_count,
    } = feed;

    return (<Card elevation={0} className={classes.drawer}>
      
      <CardHeader
        avatar={<Avatar>{name[0]}</Avatar>}
        title={`${name} (${user_id})`}
        subheader={localDate(created_at)}
      />

      <CardContent className={classes.feedContent}>
        { paid_node && (

          <Typography component="span" className={classes.amoutShow}>
            文字收费：{paid_node.amount  / 100} 元
          </Typography>
        )
        }
        {content}
      </CardContent>

      { images.map(({
        id,
        paid_node
      }) => (<CardMedia key={id} image={''} className={classes.media}>
        <img
          src={createRequestURI(`files/${id}`)}
          className={classes.drawerImage}
        />
        { paid_node && (<CardHeader
          title={
            paid_node.extra === 'read' ? '查看' : '下载' +
            '收费：' +
            (paid_node.amount * window.FEED.walletRatio / 100 / 100)
            + ' 元'
          }
          className={classes.drawerImageTitle}
        />)}
      </CardMedia>
      )) }

      <CardContent className={classes.drawerRow}>
        <Chip className={classes.chip} avatar={<Avatar><FavoriteIcon /></Avatar>} label={digg_count || 0} />
        <Chip className={classes.chip}  onClick={ () => this.setFeedId(feed_id) } avatar={<Avatar><Forum /></Avatar>} label={comment_count || 0} />
      </CardContent>

    </Card>);

  }

  setFeedId (feed_id) {
    this.setState({ params: { ...this.state.params, feed: feed_id } });
    this.handleGetDatas();
  }

  handleOpenPinnedDialog (comment) {
    this.setState({
      pinned: comment
    });
  }

  handleAcceptPinned (comment) {
    let pinned = this.handleGetPinned(comment.id);
    request.patch(
      createRequestURI(`comments/${pinned.target}/pinneds/${pinned.id}`),
      {
        validateStatus: status => status === 201
      }
    )
      .then(( { data: { data } }) => {
        this.handleSnackbar({
          message: '操作成功!',
          open: true,
        });
        delete data.comment;
        this.setState({
          ...this.state,
          pinned: null
        });
        let index = _.findIndex(this.state.pinneds, { id: pinned.id });
        if(index !== -1) {
          this.setState({
            ...this.state,
            pinneds: {
              ...this.state.pinneds,
              [index]: {
                ...data
              }
            }
          });
        }
      })
      .catch(() => {
        this.handleSnackbar({
          message: '操作失败!',
          open: true,
        });
      });
  }

  doPinnedAudit (comment = null) {
    if (! comment) {
      return null;
    }

    let pin = this.handleGetPinned(comment.id);

    if(!pin ) {
      return (
        <section>
          <DialogTitle>{'评论置顶审核操作'}</DialogTitle>
          <DialogContent>
            <DialogContentText>
              该评论未置顶，如果您要人工置顶，请在下方输入要置顶的天数
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
            <Button  onClick={() =>this.handleRequestClose()} color="primary">
              取消
            </Button>
            <Button  onClick={() => this.handleSetPinned(comment)} color="primary" autoFocus>
              确定
            </Button>
          </DialogActions>
        </section>
      );
    }

    const {
      user: {
        name = '',
      } = {},
      expires_at = null,
      amount = 0,
      day = 0,
    } = pin;

    if (expires_at && new Date(expires_at) < new Date()) {
      return (
        <section>
          <DialogTitle>{'评论置顶审核操作'}</DialogTitle>
          <DialogContent>
            <DialogContentText>
              该评论未置顶，或置顶时间已到，或已经被拒绝了申请，如果您要人工置顶，请在下方输入要置顶的天数
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
            <Button  onClick={() =>this.handleRequestClose()} color="primary">
              取消
            </Button>
            <Button  onClick={() => this.handleSetPinned(comment)} color="primary" autoFocus>
              确定
            </Button>
          </DialogActions>
        </section>
      );
    } else if (pin && (expires_at && new Date(expires_at) > new Date())) {
      return(
        <section>
          <DialogTitle>{'评论置顶操作'}</DialogTitle>
          <DialogContent>
            <DialogContentText>
              评论作者: {name}，{
                amount !== 0 ? `花费${showAmount(amount)},申请该条评论置顶${day}天` : `该条评论已由管理员置顶${day}天` },到期时间{localDate(expires_at)},
              是否需要撤销置顶?
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button  onClick={() =>this.handleRequestClose()} color="primary">
              取消
            </Button>
            <Button  onClick={() => this.handleRejectPinned(comment)} color="primary" autoFocus>
              撤销
            </Button>
          </DialogActions>
        </section>
      );
    } else {
      return(
        <section>
          <DialogTitle>{'评论置顶审核操作'}</DialogTitle>
          <DialogContent>
            <DialogContentText>
              评论作者: {name} 花费 {showAmount(amount)}，申请该条评论置顶{day}天时间，是否同意？
            </DialogContentText>
          </DialogContent>
          <DialogActions>
            <Button  onClick={() =>this.handleRejectPinned(comment)} color="primary">
              拒绝
            </Button>
            <Button  onClick={() =>this.handleRequestClose()} color="primary">
              取消
            </Button>
            <Button  onClick={() => this.handleAcceptPinned(comment)} color="primary" autoFocus>
              同意
            </Button>
          </DialogActions>
        </section>
      );
    }
  }

  handleRequestClose() {
    this.setState({
      ...this.state,
      pinned: null
    });
  }

  handleDayChange = event => {
    this.setState({
      ...this.state,
      pinnedDay: event.target.value
    });
  };
  /**
   * 撤销置顶
   * @param  {[type]} id [description]
   * @return {[type]}    [description]
   */
  handleRejectPinned (comment) {
    let pinned = this.handleGetPinned(comment.id);
    request.delete(
      createRequestURI(`comments/pinneds/${pinned.id}`),
      {
        ...{
          action: 'reject'
        }
      },
      {
        validateStatus: status => status === 204
      }
    )
      .then( () => {
        this.setState({
          ...this.state,
          pinned: null
        });
        this.handleSnackbar({
          message: '操作成功!',
          open: true,
        });
        this.handleRequestClose();
        let index = _.findIndex(this.state.pinneds, { target: pinned.target });
        if(index !== -1) {
          this.state.pinneds.splice(index, 1);
        }

        this.setState({
          ...this.state
        });
      })
      .catch( () => {
        this.setState({
          ...this.state,
          pinnedDialog:
        {
          ...this.state.pinnedDialog,
          ing: false
        }
        });
        this.handleSnackbar({
          message: '操作失败!',
          open: true,
        });
      });
  }

  /**
   * 设置置顶
   * @param  {[type]} feed_id [description]
   * @param  {[type]} pinned  [description]
   * @return {[type]}         [description]
   */
  handleSetPinned = (comment) => {
    const { pinnedDay: day = 0 } = this.state;
    if(day === 0) {
      return;
    }
    request.post(
      createRequestURI(`comments/${comment.id}/pinned`),
      {
        day
      },
      {
        validateStatus: status => status === 201
      }
    )
      .then( ({ data: { data } }) => {
        this.handleSnackbar({
          message: '设置成功!',
          open: true,
        });
        let state = this.state;
        state.pinned = null;
        let index = _.findIndex(this.state.pinneds, {id: data.target});
        if(index !== -1) {
          state.pinneds[index] = {
            ...this.state.pinneds[index],
            ...data
          };
        } else {
          state.pinneds.push(data);
        }

        this.setState(state);
      })
      .catch( () => {
        this.handleSnackbar({
          message: '操作失败了!',
          open: true,
        });
      });
  };

  makeImages(images = []) {
    if (images.length) {
      let { id } = images.pop();

      return (
        <img src={createRequestURI(`files/${id}`)} />
      );
    }
    
    return null;
  }

  handleGetDatas () {

    this.setState({
      ...this.state,
      loading: true,
      loadMoreBtnDisabled: true,
      loadMoreBtnText: '已全部加载',
    });


    let loadMoreBtnText, loadMoreBtnDisabled;
    const { params } = this.state;

    request.get(
      createRequestURI('comments'), {
        params: {
          ...params,
          stime: params.stime ? localDateToUTC(params.stime) : '',
          etime: params.etime ? localDateToUTC(params.etime) : '',
        }
      },
      {
        validateStatus: status => status === 200
      }
    )
      .then(({ data }) => {
        if (!data.nextPage) {
          loadMoreBtnDisabled = true;
          loadMoreBtnText = '已全部加载';
        } else {
          loadMoreBtnDisabled = false;
          loadMoreBtnText = '加载更多';
        }
      
        this.setState({
          comments: data.comments,
          pinneds: data.pinneds,
          loading: false,
          nextPage: data.nextPage,
          currentPage: data.current_page,
          lastPage: data.lastPage,
          total: data.total,
          loadMoreBtnDisabled,
          loadMoreBtnText
        });
      });
  }

  componentDidMount () {
    const search = this.props.location.search;
    if(search) {
      this.setState({
        params: { ...this.state.params, ...getQuery(search) }
      });
    }
    this.handleGetDatas();
  }
}

export default withStyles(styles)(Comment);