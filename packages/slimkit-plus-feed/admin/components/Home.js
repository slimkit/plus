/**
 * Feed component home page.
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types'

import withStyles from 'material-ui/styles/withStyles';
import Grid from 'material-ui/Grid';
import Card, { CardHeader, CardContent, CardMedia, CardActions } from 'material-ui/Card';
import Typography from 'material-ui/Typography';
import Button from 'material-ui/Button';
import IconButton from 'material-ui/IconButton';
import SvgIcon from 'material-ui/SvgIcon';
import { blue } from 'material-ui/colors';
import { Link } from 'react-router-dom';
import { FormControl, FormLabel, FormControlLabel } from 'material-ui/Form';
import Dialog, { DialogContent, DialogActions } from 'material-ui/Dialog';
import { CircularProgress } from 'material-ui/Progress';
import Snackbar from 'material-ui/Snackbar';

import FavoriteIcon from 'material-ui-icons/Favorite';
import Comment from 'material-ui-icons/Comment';
import RssFeed from 'material-ui-icons/RssFeed';
import Radio, { RadioGroup } from 'material-ui/Radio';
import Popover from 'material-ui/Popover';
import Chip from 'material-ui/Chip';
import Drawer from 'material-ui/Drawer';
import Divider from 'material-ui/Divider';
import ListSubheader from 'material-ui/List/ListSubheader';
import List, { ListItem, ListItemIcon, ListItemText } from 'material-ui/List';
import Collapse from 'material-ui/transitions/Collapse';import ExpandLess from 'material-ui-icons/ExpandLess';
import ExpandMore from 'material-ui-icons/ExpandMore';

import request, { createRequestURI } from '../utils/request';
import { showAmount } from '../utils/balance';


const styles = (theme:object) => ({
  root: {
    padding: theme.spacing.unit,
    width: '100%',
    margin: 0
  },
  flexGrow: {},
  link: {
    color: blue[400],
    textDecoration: 'none',
    display: 'flex'
  },
  action: {
    padding: `0 ${theme.spacing.unit * 2}px`
  },
  drawer: {
    width: '100%',
    maxWidth: 360,
    background: theme.palette.background.paper,
  },
  nested: {
    paddingLeft: theme.spacing.unit * 4,
  },
  icon: {
    padding: `0 ${theme.spacing.unit}px`
  },
  text: {
    color: blue[400]
  }
});

class Home extends Component
{
  static propTypes = {
    classes: PropTypes.object.isRequired
  };

  state = {
    feeds: 0,
    comments: 0,
    status: true,
    loading: false,
    reward: 'true',
    close: {
      reward: {
        status: 'true',
        ing: false
      }
    },
    open: false,
    type: '',
    drawerOpen: false,
    data: {
      feedsCount: 0,
      commentsCount: 0,
      payFeedsCount: 0
    }
  };

  componentDidMount () {
    request.get(createRequestURI('statistics'), {
      validataStatus: status => status === 200
    }).then(({ data } = {} ) => {
      this.setState({
        feeds: data.feedsCount || 0,
        comments: data.commentsCount || 0,
        reward: data.status.reward ? 'true' : 'false'
      })
    }).catch( error => {

    });
  };

  handleRequestClose =  () => {
    this.setState({
      open: false
    })
  }

  /**
   * 修改打赏开关
   * @param  {[type]} key [description]
   * @return {[type]}     [description]
   */
  handleRewardChange = key => (event, value) => {
    this.state.close.reward.status = value;
    this.setState(this.state);
    if (value === 'true') {
      this.handleRewardStore();
    }
  };

  // 打赏开关控制
  handleRewardStore () {
    this.state.close.reward.ing = true;
    this.setState(this.state);
    const { close: { reward: { status = 'false' } = {} } = {} } = this.state;
    request.patch(createRequestURI('status/reward'), {
      reward: status === 'false' ? false : true
    }, {
      validataStatus: status => status === 201
    })
    .then( () => {
      this.state.close.reward.ing = false;
      this.state.close.reward.status = 'true';
      this.state.reward = status;
      this.state.open = true;
      this.setState(this.state);
    })
    .catch(({ response: { data = {} } }) => {
      console.log(data);
    })
  };

  handleCloseRewardDialog () {
    this.state.close.reward.status = 'true';
    this.state.close.reward.ing = false;
    this.setState(this.state);
  };
  handleClick = (type) => {
    request.get(createRequestURI(`statistics?type=${type}`), {
      validataStatus: status => status === 200
    })
    .then(({ data = {} }) => {
      this.setState({
        data: { ...data },
        type: type
      });
    })
    this.setState({
      type: type
    });
  }

  render() {
    const { classes } = this.props;
    const { feeds = 0, comments = 0, close, open, data, type, drawerOpen } = this.state;

    return (
      <div>
        <Drawer
          anchor="right"
          open={drawerOpen}
          onRequestClose={() => {this.setState({ drawerOpen: false, type: ''})}}
        >
          <div tabIndex={0} role="button">
            <List className={classes.drawer} subheader={<ListSubheader>动态数据统计</ListSubheader>}>
              <ListItem
                className={classes.button} 
                button
                onTouchTap={ () => this.handleClick('all')}
              >
                <ListItemText inset primary="全部" />
                {type === 'all' ? <ExpandLess /> : <ExpandMore />}
              </ListItem>
              <Collapse in={type === 'all'} transitionDuration="auto" unmountOnExit>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=all">
                    <ListItemText className={classes.text} inset primary="动态数量" secondary={data.feedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=all&pay=paid">
                    <ListItemText className={classes.text} inset primary="收费动态" secondary={data.payFeedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=all">
                    <ListItemText className={classes.text} inset primary="评论数量" secondary={data.commentsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=all&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶动态" secondary={data.topFeed} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=all&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶评论" secondary={data.topComment} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <ListItemText className={classes.text} inset primary="收到金额" secondary={showAmount(data.payCount)} />
                </ListItem>
              </Collapse>
              <ListItem
                className={classes.button} 
                button
                onTouchTap={ () => this.handleClick('today')}
              >
                <ListItemText inset primary="今天" />
                {type === 'today' ? <ExpandLess /> : <ExpandMore />}
              </ListItem>
              <Collapse in={type === 'today'} transitionDuration="auto" unmountOnExit>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=today">
                    <ListItemText className={classes.text} inset primary="动态数量" secondary={data.feedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=today&pay=paid">
                    <ListItemText className={classes.text} inset primary="收费动态" secondary={data.payFeedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=today">
                    <ListItemText className={classes.text} inset primary="评论数量" secondary={data.commentsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=today&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶动态" secondary={data.topFeed} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=today&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶评论" secondary={data.topComment} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <ListItemText className={classes.text} inset primary="收到金额" secondary={showAmount(data.payCount)} />
                </ListItem>
              </Collapse>
              <ListItem
                className={classes.button} 
                button
                onTouchTap={ () => this.handleClick('yesterday')}
              >
                <ListItemText inset primary="昨天" />
                {type === 'yesterday' ? <ExpandLess /> : <ExpandMore />}
              </ListItem>
              <Collapse in={type === 'yesterday'} transitionDuration="auto" unmountOnExit>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=yesterday">
                    <ListItemText inset primary="动态数量" secondary={data.feedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=yesterday&pay=paid">
                    <ListItemText inset primary="收费动态" secondary={data.payFeedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=yesterday">
                    <ListItemText inset primary="评论数量" secondary={data.commentsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=yesterday&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶动态" secondary={data.topFeed} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=yesterday&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶评论" secondary={data.topComment} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <ListItemText inset primary="收到金额" secondary={showAmount(data.payCount)} />
                </ListItem>
              </Collapse>
              <ListItem
                className={classes.button} 
                button
                onTouchTap={ () => this.handleClick('week')}
              >
                <ListItemText inset primary="过去一周" />
                {type === 'week' ? <ExpandLess /> : <ExpandMore />}
              </ListItem>
              <Collapse in={type === 'week'} transitionDuration="auto" unmountOnExit>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=week">
                    <ListItemText inset primary="动态数量" secondary={data.feedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=week&pay=paid">
                    <ListItemText inset primary="收费动态" secondary={data.payFeedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=week">
                    <ListItemText inset primary="评论数量" secondary={data.commentsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=week&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶动态" secondary={data.topFeed} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=week&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶评论" secondary={data.topComment} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <ListItemText inset primary="收到金额" secondary={showAmount(data.payCount)} />
                </ListItem>
              </Collapse>
              <ListItem
                className={classes.button} 
                button
                onTouchTap={ () => this.handleClick('lastDay')}
              >
                <ListItemText inset primary="截止昨天" />
                {type === 'lastDay' ? <ExpandLess /> : <ExpandMore />}
              </ListItem>
              <Collapse in={type === 'lastDay'} transitionDuration="auto" unmountOnExit>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=lastDay">
                    <ListItemText inset primary="动态数量" secondary={data.feedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=lastDay&pay=paid">
                    <ListItemText inset primary="收费动态" secondary={data.payFeedsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=lastDay">
                    <ListItemText inset primary="评论数量" secondary={data.commentsCount} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/feeds?type=lastDay&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶动态" secondary={data.topFeed} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <Link className={classes.link} to="/comments?type=lastDay&top=yes">
                    <ListItemText className={classes.text} inset primary="置顶评论" secondary={data.topComment} />
                  </Link>
                </ListItem>
                <ListItem button className={classes.nested}>
                  <ListItemText inset primary="收到金额" secondary={showAmount(data.payCount)} />
                </ListItem>
              </Collapse>
            </List>
          </div>
        </Drawer>
        <Grid container className={classes.root}>
          <Grid item xs={12} sm={6}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  扩展开关
                </Typography>
                <Typography component="p">
                  关闭后，用户无法进行动态部分相关操作
                </Typography>
              </CardContent>

              <CardActions>
                <FormControl component="fieldset"  className={classes.action}>
                  <FormLabel component="legend">打赏开关</FormLabel>
                  <RadioGroup
                    row
                    name="reward"
                    value={this.state.reward}
                    onChange={this.handleRewardChange()}
                  >
                    <FormControlLabel value={'true'} control={<Radio />} label="开启" />
                    <FormControlLabel value={'false'} control={<Radio />} label="关闭" />
                  </RadioGroup>
                </FormControl>
              </CardActions>

            </Card>
          </Grid>
          <Grid item xs={12} sm={6}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  动态统计
                </Typography>
                <Typography component="p">
                  系统中所有动态的统计数据(不包括已删除的动态)
                </Typography>
              </CardContent>

              <CardActions>
                <Button disabled className={classes.icon}>
                  <RssFeed color={blue[400]} />&nbsp;{feeds}
                </Button>
                <Button disabled className={classes.icon}>
                  <Comment color={blue[400]} />&nbsp;{comments}
                </Button>

                <div className={classes.flexGrow} />
                <Button 
                  dense 
                  color="primary"
                  onTouchTap={() => { this.setState({drawerOpen: true}) }}
                >
                  数据统计
                </Button>
                <div className={classes.flexGrow} />
                <Button dense color="primary">
                  <Link to="/feeds" className={classes.link}>管理动态</Link>
                </Button>

              </CardActions>

            </Card>
          </Grid>
          <Grid item xs={12} sm={6}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  动态回收站
                </Typography>
                <Typography component="p">
                  所有被放入回收站的动态管理
                </Typography>
              </CardContent>

              <CardActions>
                <Button dense color="primary">
                  <Link to="/deleteFeeds" className={classes.link}>管理动态回收站</Link>
                </Button>

              </CardActions>

            </Card>
          </Grid>
          {/*<Grid item xs={12} sm={6}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  动态评论回收站
                </Typography>
                <Typography component="p">
                  被加入回收站的动态评论
                </Typography>
              </CardContent>

              <CardActions>
                <Button dense color="primary">
                  <Link to="/deleteComments" className={classes.link}>管理评论回收站</Link>
                </Button>

              </CardActions>

            </Card>
          </Grid>*/}
        </Grid>
        <Dialog open={close.reward.status === 'false'}>
          <DialogContent>确定要关闭打赏吗？</DialogContent>
          <DialogActions>
            { close.reward.ing
              ? <Button disabled>取消</Button>
              : <Button onTouchTap={() =>this.handleCloseRewardDialog()}>取消</Button>
            }
            { close.reward.ing
              ? <Button disabled><CircularProgress size={14} /></Button>
              : <Button color="primary" onTouchTap={() => this.handleRewardStore()}>关闭</Button>
            }
          </DialogActions>
        </Dialog>
        <Snackbar
          anchorOrigin={{ vertical: 'bottom', horizontal: 'center' }}
          open={open}
          onRequestClose={this.handleRequestClose}
          SnackbarContentProps={{
            'aria-describedby': 'message-id',
          }}
          message={<span id="message-id">操作成功</span>}
        />
      </div>
    );
  }
}

export default withStyles(styles)(Home);
