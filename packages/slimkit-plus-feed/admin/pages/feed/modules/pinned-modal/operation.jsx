import React from 'react';
import classNames from 'classnames';
import propTypes from './prop-types';
import Paper from '@material-ui/core/Paper'
import Typography from '@material-ui/core/Typography';;
import HeaderBar from './modal-header-bar';
import Button from '@material-ui/core/Button';
import CircularProgress from '@material-ui/core/CircularProgress';
import { passPinned, rejectPinned } from '../../../../api/feed';
import { showAmount } from '../../../../utils/balance';

class Operation extends React.Component {

    static propTypes = propTypes;

    state = {
        passing: false,
        rejecting: false,
    }

    getPinned() {
        let { payload: { pinned } } = this.props;

        return pinned;
    }

    onPass() {
        this.setState({ passing: true });
        passPinned(this.getPinned().id)
            .then(() => {
                this.props.message.onShow({
                    open: true,
                    type: 'success',
                    text: '操作成功！'
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

    onReject() {
        this.setState({ rejecting: true });
        rejectPinned(this.getPinned().id)
            .then(() => {
                this.props.message.onShow({
                    open: true,
                    type: 'success',
                    text: '操作成功！'
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
        let pinned = this.getPinned();

        return (
            <Paper classes={{
                root: classes.box,
            }}>
                <HeaderBar
                    classes={classes}
                    title="置顶审核"
                    onClose={onClose}
                    disabled={this.state.passing || this.state.rejecting}
                />
                <div className={classes.wrap}>
                    <Typography variant="subtitle1">
                        该动态使用 `{showAmount(pinned.amount)}`申请置顶 `{pinned.day}` 天
                    </Typography>
                    
                    <div className={classes.modalActions}>
                        <div className={classNames(classes.actionsWrapper, classes.actionsFab)}>
                            <Button
                                variant="contained"
                                color="primary"
                                disabled={this.state.passing || this.state.rejecting}
                                onClick={this.onPass.bind(this)}
                            >
                                同&nbsp;&nbsp;意
                            </Button>
                            {this.state.passing && (
                                <CircularProgress size={24} className={classes.buttonProgress} />
                            )}
                        </div>

                        <div className={classNames(classes.actionsWrapper, classes.actionsFab)}>
                            <Button
                                variant="contained"
                                color="secondary"
                                disabled={this.state.passing || this.state.rejecting}
                                onClick={this.onReject.bind(this)}
                            >
                                拒&nbsp;&nbsp;绝
                            </Button>
                            {this.state.rejecting && (
                                <CircularProgress size={24} className={classes.buttonProgress} />
                            )}
                        </div>
                    </div>
                </div>
            </Paper>
        );
    }
}

export default Operation;
