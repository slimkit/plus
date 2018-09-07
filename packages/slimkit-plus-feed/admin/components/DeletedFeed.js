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
import Snackbar from '@material-ui/core/Snackbar';
import Avatar from '@material-ui/core/Avatar';
import Button from '@material-ui/core/Button';
import IconButton from '@material-ui/core/IconButton';
import CircularProgress from '@material-ui/core/CircularProgress';
import Drawer from '@material-ui/core/Drawer';
import Chip from '@material-ui/core/Chip';
import FormControl from '@material-ui/core/FormControl';
import Input from '@material-ui/core/Input';

import FavoriteIcon from '@material-ui/icons/Favorite';
import Forum from '@material-ui/icons/Forum';
import Delete from '@material-ui/icons/Delete';
import CloseIcon from '@material-ui/icons/Close';
import SettingsBackupRestore from '@material-ui/icons/SettingsBackupRestore';

import request, { createRequestURI } from '../utils/request';

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
  drawerImageTitle: {
    width: '100%',
    position: 'absolute',
    bottom: 0,
    background: 'rgba(255, 255, 255, .4)',
    color: '#fff'
  },
  drawerRow: {
    display: 'flex',
    justifyContent: 'flex-start',
    flexWrap: 'wrap',
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
  title: {
    width: '100%',
    color: theme.palette.grey[500]
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
});

class DeletedFeed extends Component
{
  static propTypes = {
    classes: PropTypes.object.isRequired,
  };

  state = {
    feeds: [],
    del: {
      feed: null,
      ing: false,
    },
    restore: {
      feed: null,
      ing: false
    },
    snackbar: {
      open: false,
      message: '',
      vertical: 'bottom',
      horizontal: 'right',
    },
    drawer: null,
    loadMoreBtnText: '加载更多',
    loadMoreBtnDisabled: false,
    loading: false,
    params: {
      limit: 16,
      keyword: '',
      user: 0,
      name: '',
      current_page: 1,
      last_page: 1,
      total: 0
    },
  };

  handleKeywordChange = e => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        keyword: e.target.value
      }
    });
  };

  handleUserChange = e => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        user: e.target.value
      }
    });
  };

  handleNameChange = e => {
    this.setState({
      ...this.state,
      params: {
        ...this.state.params,
        name: e.target.value
      }
    });
  };

  render() {
    const { classes } = this.props;
    const { feeds = [], del, restore, snackbar, drawer, params } = this.state;

    return (
      <div>
        <Grid  container className={classes.root}>
          <div className={classes.container}>
            <form className={classes.container} autoComplete="off">
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>动态筛选</h5>
                <Input
                  placeholder="关键字"
                  aria-label="Description"
                  onChange={ this.handleKeywordChange }
                  value={params.keyword}
                />
              </FormControl>
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>用户ID</h5>
                <Input
                  placeholder="用户ID"
                  aria-label="Description"
                  type={'number'}
                  onChange={ this.handleUserChange }
                  value={params.user || ''}
                />
              </FormControl>
              <FormControl className={classes.formControl}>
                <h5 className={classes.title}>用户昵称</h5>
                <Input
                  placeholder="用户昵称"
                  aria-label="Description"
                  onChange={ this.handleNameChange }
                  value={params.name}
                />
              </FormControl>
              {
                params.user_id ?
                  <FormControl className={classes.formControl}> 
                    <Chip
                      label={`用户: ${params.name}`}
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
          { feeds.map(({
            id,
            created_at,
            feed_content: content,
            user: { name, id: user_id } = {},
            like_count: digg_count = 0,
            feed_comment_count: comment_count = 0,
          }) => (

            <Grid  item xs={12} sm={6} key={id}>
              <Card>

                <CardHeader
                  avatar={<Avatar>{name[0]}</Avatar>}
                  title={`${name} (${user_id})`}
                  subheader={created_at}
                />

                <CardContent  onClick={() => this.handleRequestDrawer(id)}>
                  <Typography>
                    #{id}
                  </Typography>
                  {content}
                </CardContent>

                <CardActions>

                  <Button disabled>
                    <FavoriteIcon />&nbsp;{digg_count}
                  </Button>

                  <Button disabled>
                    <Forum />&nbsp;{comment_count}
                  </Button>
                  <IconButton
                    title={'恢复'}
                    onClick={() => this.handlePushRestore(id)}
                  >
                    <SettingsBackupRestore />
                  </IconButton>
                  <div className={classes.flexGrow} />

                  <IconButton
                    onClick={() => this.handlePushDelete(id)}
                  >
                    <Delete />
                  </IconButton>

                </CardActions>

              </Card>
            </Grid>

          )) }

        </Grid>
        <Button
          raised="true"
          color="primary"
          className={classes.loadMoreBtn}
          onClick={() => this.handleLoadMoreFeed()}
          disabled={this.state.loadMoreBtnDisabled}
        >
          共[{params.total}]条动态，当前第[{params.current_page}]页/共[{params.last_page}]页{this.state.loadMoreBtnText}
          <CircularProgress
            className={this.state.loading ? classes.progress : classes.progeessHide}
            color="secondary"
            size={30}
          />
        </Button>
        <Dialog open={!! del.feed}>
          <DialogContent>确定要删除吗？</DialogContent>
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
        <Dialog open={!! restore.feed}>
          <DialogContent>确定要恢复吗？</DialogContent>
          <DialogActions>
            { restore.ing
              ? <Button disabled>取消</Button>
              : <Button  onClick={() => this.handlePushClose()}>取消</Button>
            }
            { restore.ing
              ? <Button disabled><CircularProgress size={14} /></Button>
              : <Button color="primary"  onClick={() => this.handleRestore()}>恢复</Button>
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
          {this.makeDrawerContent(drawer)}
        </Drawer>

      </div>
    );
  }

  handleRequestDrawer(feed) {
    this.setState({
      ...this.state,
      drawer: feed,
    });
  }

  handleDrawerClose() {
    this.setState({
      ...this.state,
      drawer: null,
    });
  }

  makeDrawerContent(feed_id = null) {
    if (! feed_id) {
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

    if (! feed) {
      return null;
    }

    const { classes } = this.props;
    const {
      user: {
        name,
        id: user_id
      },
      created_at,
      feed_content: content,
      images = [],
      feed_digg_count: digg_count,
      feed_comment_count: comment_count,
    } = feed;

    return (<Card elevation={0} className={classes.drawer}>
      
      <CardHeader
        avatar={<Avatar>{name[0]}</Avatar>}
        title={`${name} (${user_id})`}
        subheader={created_at}
      />

      <CardContent>{content}</CardContent>

      { images.map(({
        id,
        paid_node
      }) => (<CardMedia key={id} image=''>
        <img
          src={createRequestURI(`files/${id}`)}
          className={classes.drawerImage}
        />
        { paid_node && (<CardHeader
          title={
            paid_node.extra === 'read' ? '查看' : '下载' +
            '收费：' +
            (paid_node.amount * window.FEED.walletRatio / 100)
          }
          className={classes.drawerImageTitle}
        />)}
      </CardMedia>
      )) }

      <CardContent className={classes.drawerRow}>
        <Chip className={classes.chip} avatar={<Avatar><FavoriteIcon /></Avatar>} label={digg_count} />
        <Chip className={classes.chip} avatar={<Avatar><Forum /></Avatar>} label={comment_count} />
      </CardContent>

    </Card>);

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

  handlePushDelete(feed) {
    const state = this.state;
    this.setState({
      ...state,
      del: { feed, ing: false }
    });
  }

  handlePushRestore(feed) {
    const state = this.state;
    this.setState({
      ...state,
      restore: { feed, ing: false }
    });
  }

  handlePushClose() {
    this.setState({
      ...this.state,
      del: { feed: null, ing: false },
      restore: { feed: null, ing: false}
    });
  }

  handleRestore() {
    const { restore: { feed } } = this.state;
    this.setState({
      ...this.state,
      restore: { feed, ing: true }
    });

    request.patch(createRequestURI(`feeds?feed=${feed}`),{
      validateStatus: status => status === 201
    }).then( () => {
      this.handlePushClose();
      this.handlePullFeed(feed);
      this.handleSnackbar({
        message: '恢复成功',
        open: true
      });
    }).catch(({ response: { data: { message: [ message = '删除失败，请检查网络！' ] = [] } = {} } = {} } = {}) => {
      this.handlePushClose();
      this.handleSnackbar({
        message,
        open: true,
      });
    });
  }

  handleDelete() {
    const { del: { feed } } = this.state;
    this.setState({
      ...this.state,
      del: { feed, ing: true }
    });
    request.delete(
      createRequestURI(`feeds?feed=${feed}`),
      { validateStatus: status => status === 204 }
    ).then(() => {
      this.handlePushClose();
      this.handlePullFeed(feed);
      this.handleSnackbar({
        message: '删除成功!',
        open: true,
      });
    }).catch(({ response: { data: { message: [ message = '删除失败，请检查网络！' ] = [] } = {} } = {} } = {}) => {
      this.handlePushClose();
      this.handleSnackbar({
        message,
        open: true,
      });
    });
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
    this.handleSnackbar({ open: false, });
  }

  handleGetDatas  (type = 'new') {
    this.setState({
      ...this.state,
      loading: true,
      loadMoreBtnDisabled: true,
      loadMoreBtnText: '加载中...'
    });

    const { params } = this.state;

    request.get(
      createRequestURI('deleted-feeds'),
      {
        params: {
          ...params,
          page: type === 'loadmore' ? params.current_page + 1 : 1
        }
      },
      {
        validateStatus: status => status === 200
      }
    )
      .then(({ data }) => {
        let loadMoreBtnText = '加载更多', loadMoreBtnDisabled = false, loading = false;
        if (data.data.length < params.limit || data.current_page === data.last_page) {
          loadMoreBtnDisabled = true;
          loadMoreBtnText = '已加载全部';
        }
        let feeds = this.state.feeds;
        if(type === 'loadmore') {
          feeds = [ ...feeds, ...data.data ];
        } else {
          feeds = data.data;
        }

        this.setState({
          ...this.state,
          ...{
            feeds: feeds,
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
  }

  // 加载更多
  handleLoadMoreFeed() {
    this.handleGetDatas('loadmore');
  }

  componentDidMount() {
    this.handleGetDatas();
  }
}

export default withStyles(styles)(DeletedFeed);
