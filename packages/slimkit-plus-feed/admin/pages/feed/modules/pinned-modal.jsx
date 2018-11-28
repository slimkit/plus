import React from 'react';
import PropTypes from 'prop-types';
import staticPropTypes from './pinned-modal/prop-types';
import Modal from '@material-ui/core/Modal';

// styles
import withStyles from '@material-ui/core/styles/withStyles';
import styles from './pinned-modal/styles';

// Components
import { SettingPinnedModal, UnsettingPinnedModal, OperationPinnedModal } from './pinned-modal/';

const components = {
    setting: SettingPinnedModal,
    unsetting: UnsettingPinnedModal,
    operation: OperationPinnedModal,
};

function CheckPayload(props) {
    if (props.hide) {
        return null;
    }

    let { component: Component } = props;

    return <Component {...props.payload} />;
}

class PinnedModal extends React.Component {

    static propTypes = {
        ...staticPropTypes,
        type: PropTypes.oneOf(['setting', 'unsetting', 'operation']).isRequired,
        payload: PropTypes.any,
    };

    render() {

        let { classes, type, payload, ...props } = this.props;
        let { [type]: Component } = components;

        return (
            <Modal open={!! payload}>
                <div className={classes.root}>
                    <CheckPayload
                        hide={! payload}
                        component={Component}
                        payload={{
                            ...props,
                            classes,
                            payload,
                        }}
                    />
                </div>
            </Modal>
        );
    }
}

export default withStyles(styles)(PinnedModal);