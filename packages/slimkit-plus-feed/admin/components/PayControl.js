/**
 * The file is admin feeds pay-control manage page.
 */

import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Grid from '@material-ui/core/Grid';
import Card from '@material-ui/core/Card';
import CardContent from '@material-ui/core/CardContent';
import CardActions from '@material-ui/core/CardActions';
import Typography from '@material-ui/core/Typography';
import Dialog from '@material-ui/core/Dialog';
import DialogContent from '@material-ui/core/DialogContent';
import DialogActions from '@material-ui/core/DialogActions';
import Snackbar from '@material-ui/core/Snackbar';
import Button from '@material-ui/core/Button';
import IconButton from '@material-ui/core/IconButton';
import FormControlLabel from '@material-ui/core/FormControlLabel';
import Switch from '@material-ui/core/Switch';
import CircularProgress from '@material-ui/core/CircularProgress';
import TextField from '@material-ui/core/TextField';
import CloseIcon from '@material-ui/icons/Close';

import withStyles from '@material-ui/core/styles/withStyles';
import request, { createRequestURI } from '../utils/request';

const styles = (theme) => ({
  root: {
    width: '100%',
    padding: theme.spacing.unit,
    margin: 0
  }
});

class PayControl extends Component
{
  static propTypes = {
    classes: PropTypes.object.isRequired,
  };

  state = {
    snackbar: {
      open: false,
      message: '',
      vertical: 'bottom',
      horizontal: 'right',
    },
    open: false,
    textLength: 50,
    payItems: '1,5,10',
    close: {
      open: false,
      ing: false
    }
  };
  handleChange = e => {
    this.setState({
      ...this.state,
      payItems: e.target.value
    });
  };
  handleTextChange = e => {
    this.setState({
      ...this.state,
      textLength: e.target.value
    });
  };
  handleSnackbar(snackbar = {}) {
    this.setState({
      ...this.state,
      snackbar: { ...this.state.snackbar, ...snackbar }
    });
  }
  handleSnackbarClose() {
    this.handleSnackbar({ open: false, });
  }
  saveItem () {
    const { payItems, textLength } = this.state;
    request.patch(createRequestURI('paycontrol'), {
      payItems,
      textLength
    }, {
      validataStatus: status => status === 201
    }).then( () => {
      this.handleSnackbar({
        message: '保存成功!',
        open: true,
      });
    }).catch ( () => {
      this.handleSnackbar({
        message: '保存失败!',
        open: true,
      });
    });
  }

  render () {
    let { open = false, close, snackbar } = this.state;
    const { classes } = this.props;

    return (
      <div>
        <Grid  container className={classes.root}>
          <Grid  item xs={12} sm={12}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  动态付费控制
                </Typography>
                <Typography component="p">
                  用于控制客户端发送动态是否可以设置付费,关闭付费后，选项和文字收费长度将失效
                </Typography>
              </CardContent>

              <CardActions>

                <FormControlLabel
                  control={
                    <Switch
                      color="primary"
                      checked={open}
                      onChange={ (event, checked) => !checked ? this.handleSetFalse(checked) : this.handleStatusChange (checked) }
                    />
                  }
                  label={open ? '已开启' : '已关闭'}
                />

              </CardActions>

            </Card>
          </Grid>
          <Grid  item xs={12} sm={12}>
            <Card >
              <CardContent>
                <Typography type="headline" component="h2">
                  付费选项
                </Typography>
                <Typography component="div">
                  <p>发布付费动态时的金额选项，最少为0.01元</p>
                  <p>少于0.01元时会出现意想不到的支付错误，请慎重填写</p>
                  <p>3个选项，请用半角&quot;,&quot;隔开</p>
                </Typography>
              </CardContent>

              <CardContent>
                <TextField
                  label="金额选项"
                  className={classes.textField}
                  value={this.state.payItems}
                  onChange={this.handleChange}
                  margin="normal"
                />
                <Button  onClick={() => {
                  this.saveItem();
                }}>保存</Button>
              </CardContent>

            </Card>
          </Grid>
          <Grid  item xs={12} sm={12}>
            <Card>
              <CardContent>
                <Typography type="headline" component="h2">
                  付费选项
                </Typography>
                <Typography component="div">
                  <p>付费文字大于多少个字之后开始收费</p>
                  <p>内容必须要大于这个长度，才能设置收费</p>
                </Typography>
              </CardContent>

              <CardContent>
                <TextField
                  label="收费文字长度"
                  className={classes.textField}
                  value={this.state.textLength}
                  onChange={this.handleTextChange}
                  margin="normal"
                />
                <Button  onClick={() => {
                  this.saveItem();
                }}>保存</Button>
              </CardContent>

            </Card>
          </Grid>
        </Grid>
        <Dialog open={!! close.open}>
          <DialogContent>确定要关闭收费吗吗？</DialogContent>
          <DialogActions>
            { close.ing
              ? <Button disabled>取消</Button>
              : <Button  onClick={() => this.handleCannel()}>取消</Button>
            }
            { close.ing
              ? <Button disabled><CircularProgress size={14} /></Button>
              : <Button color="primary"  onClick={() => this.handleStatusChange()}>确定</Button>
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
      </div>
    );
  }

  handleSetFalse () {
    this.setState({
      ...this.state,
      close: {
        open: true,
        ing: false
      }
    });
  }

  handleStatusChange () {
    let open = !this.state.open;
    if (!open) {
      this.setState({
        ...this.state,
        close: {
          open: true,
          ing: true
        }
      });
    }
    request.patch(createRequestURI('paycontrol'), {
      open: open
    }, {
      validataStatus: status => status === 201
    }).then( () => {
      this.handleCannel();
      this.setState({
        ...this.state,
        open: open
      });
      this.handleSnackbar({
        message: '保存成功!',
        open: true,
      });
    }).catch ( () => {
      this.handleSnackbar({
        message: '保存失败!',
        open: true,
      });
    });
  }

  handleCannel () {
    this.setState({
      close: {
        open: false,
        ing: false
      }
    });
  }

  componentDidMount () {
    request.get(createRequestURI('paycontrol'), {
      validataStatus: status => status === 200
    }).then(({ data = {} }) => {
      this.setState({
        open: data.open,
        payItems: data.payItems,
        textLength: data.textLength
      });
    }).catch( () => {
      alert('获取配置信息失败');
    });
  }
}

export default withStyles(styles)(PayControl);