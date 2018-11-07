import React from 'react';
import classNames from 'classnames';
import propTypes from './prop-types';
import Paper from '@material-ui/core/Paper'
import Typography from '@material-ui/core/Typography';;
import HeaderBar from './modal-header-bar';
import Button from '@material-ui/core/Button';
import CircularProgress from '@material-ui/core/CircularProgress';
import { localDate } from '../../../../utils/dateProcess';
import { unsetPinned } from '../../../../api/feed';

class Unsetting extends React.Component {

    static propTypes = propTypes;

    state = {
        submiting: false,
    }

    getPinned() {
        let { payload: { pinned } } = this.props;

        return pinned;
    }

    onSubmit() {
        this.setState({ submiting: true });
        unsetPinned(this.props.payload.id)
            .then(() => {
                this.props.message.onShow({
                    open: true,
                    type: 'success',
                    text: '取消置顶成功！'
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
                    title="取消动态置顶"
                    onClose={onClose}
                    disabled={this.state.submiting}
                />
                <div className={classes.wrap}>
                    <Typography variant="subtitle1">
                        该动态当前为置顶状，到期时间为: <br />
                        <span
                            style={{
                                fontSize: '2rem',
                            }}
                        >
                            { localDate(pinned.expires_at) }
                        </span>
                    </Typography>
                    
                    <div className={classes.modalActions}>
                        <div className={classNames(classes.actionsWrapper, classes.actionsFab)}>
                            <Button
                                variant="contained"
                                color="primary"
                                disabled={this.state.submiting}
                                onClick={this.onSubmit.bind(this)}
                            >
                                取消置顶
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
                            关闭弹窗
                        </Button>
                    </div>

                </div>
            </Paper>
        );
    }
}

export default Unsetting;
