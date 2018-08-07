import React from 'react';
import PropTypes from 'prop-types';
import withStyles from '@material-ui/core/styles/withStyles';
import HeaderBar from './modules/SettingsHeaderBar';
import Paper from '@material-ui/core/Paper';
import Switch from '@material-ui/core/Switch';
import FormLabel from '@material-ui/core/FormLabel';
import FormControl from '@material-ui/core/FormControl';
import FormHelperText from '@material-ui/core/FormHelperText';
import CircularProgress from '@material-ui/core/CircularProgress';
import Snackbar from '../../components/common/Snackbar';
import { fetchReviewSwitch, reviewSwitchToggle } from '../../api/topic';

const styles = theme => ({
  paper: {
    padding: theme.spacing.unit * 3,
    paddingBottom: theme.spacing.unit * 2,
    width: '100%',
  },
  control: {
    display: 'flex',
    alignItems: 'center'
  }
});

class Settings extends React.Component {

  static propTypes = {
    classes: PropTypes.object.isRequired
  }

  state = {
    switch: false,
    loading: true,
    message: {
      type: 'success',
      text: '',
      open: false,
    }
  }

  componentDidMount() {
    fetchReviewSwitch()
      .then(({ data }) => {
        this.setState({ switch: data.switch, loading: false });
      })
      .catch(({ response: { data = { message: '获取配置失败，请刷新页面重试' } } = {} }) => {
        this.setState({message: {
          open: true,
          type: 'error',
          text: data
        }});
      });
  }

  handleToggleReviewSwitch = () => {
    this.setState({ loading: true });
    reviewSwitchToggle()
      .then(() => {
        this.setState({
          switch: !this.state.switch,
          message: { type: 'success', open: true, text: '切换成功' },
          loading: false,
        });
      })
      .catch(({ response: { data = { message: '获取配置失败，请刷新页面重试' } } = {} }) => {
        this.setState({
          loading: false,
          message: { type: 'error', open: true, text: data }
        })
      });
  }

  handleCloseMessage = () => this.setState({message: {
    ...this.state.message,
    open: false
  }})

  render() {
    let { classes } = this.props;

    return (
      <div>
        <HeaderBar />
        <Paper className={classes.paper}>
          <FormControl component="fieldset">
            <FormLabel component="legend">话题是否开启审核？</FormLabel>
            <div className={classes.control}>
              <span>开关:</span>
              <Switch
                checked={this.state.switch}
                onChange={this.handleToggleReviewSwitch}
                disabled={this.state.loading}
              />
              {this.state.loading && <CircularProgress size={24} />}
            </div>
            <FormHelperText>当开关处于开启状态时，所有创建动态话题的地方均会进入等待审核状态，如果处于关闭状态，用户创建的话题将自动进入通过状态。</FormHelperText>
          </FormControl>
        </Paper>
        <Snackbar
          open={this.state.message.open}
          message={this.state.message.text}
          type={this.state.message.type}
          onClose={this.handleCloseMessage}
        />
      </div>
    );
  }
}

export default withStyles(styles)(Settings);
