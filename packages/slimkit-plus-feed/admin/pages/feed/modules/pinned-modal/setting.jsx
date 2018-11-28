import React from 'react';
import classNames from 'classnames';
import propTypes from './prop-types';
import Paper from '@material-ui/core/Paper'
import Typography from '@material-ui/core/Typography';;
import HeaderBar from './modal-header-bar';
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';
import CircularProgress from '@material-ui/core/CircularProgress';
import { setPinned } from '../../../../api/feed';

class Setting extends React.Component {

    static propTypes = propTypes;

    state = {
        submiting: false,
        day: '',
        inputMessage: null,
    }

    getPinned() {
        let { pinned } = this.props.payload;

        return pinned;
    }

    onChange(event) {
        let day = parseInt(event.target.value);
        if (day <= 0 || isNaN(day)) {
            this.setState({
                inputMessage: '输入的天数不是合法的数值',
                day: event.target.value
            });

            return;
        }

        this.setState({ day: event.target.value, inputMessage: null });
    }

    onSubmit() {
        let day = parseInt(this.state.day);
        if (day <= 0 || isNaN(day)) {
            this.setState({
                inputMessage: '输入的天数不是合法的数值',
            });
            return;
        }

        this.setState({ submiting: true });
        setPinned(this.props.payload.id, day, { pinned: this.getPinned() })
            .then(() => {
                this.props.message.onShow({
                    open: true,
                    type: 'success',
                    text: '置顶成功！'
                });
                this.props.onRefresh({});
                this.props.onClose();
            })
            .catch(({ response: { data = '请求失败，请刷新页面重试！' } }) => {
                this.props.message.onShow({
                    type: 'error',
                    open: true,
                    text: data,
                });
            })
        ;
    }

    render() {
        
        let { classes, onClose } = this.props;

        return (
            <Paper classes={{
                root: classes.box,
            }}>
                <HeaderBar
                    classes={classes}
                    title="设置动态置顶"
                    onClose={onClose}
                    disabled={this.state.submiting}
                />
                <div className={classes.wrap}>
                    <Typography variant="subtitle1">
                        该动态当前为非置顶状，需要人工置顶请输入需要置顶的天数！
                    </Typography>
                    <TextField
                        type="number"
                        required={true}
                        fullWidth={true}
                        autoFocus={true}
                        value={this.state.day}
                        label="天数"
                        placeholder="请输入你想置顶的天数..."
                        onChange={this.onChange.bind(this)}
                        disabled={this.state.submiting}
                        error={!!this.state.inputMessage}
                        helperText={this.state.inputMessage}
                    />
                    <div className={classes.modalActions}>
                        <div className={classNames(classes.actionsWrapper, classes.actionsFab)}>
                            <Button
                                variant="contained"
                                color="primary"
                                disabled={this.state.submiting}
                                onClick={this.onSubmit.bind(this)}
                            >
                                设置
                            </Button>
                            {this.state.submiting && (
                                <CircularProgress size={24} className={classes.buttonProgress} />
                            )}
                        </div>

                        {/* 取消按钮 */}
                        <Button
                            variant="contained"
                            color="secondary"
                            className={classes.actionsFab}
                            disabled={this.state.submiting}
                            onClick={onClose}
                        >
                            取消
                        </Button>
                    </div>
                </div>
            </Paper>
        )
    }
}

export default Setting;
