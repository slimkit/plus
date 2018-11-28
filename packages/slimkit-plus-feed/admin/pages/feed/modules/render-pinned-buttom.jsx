import React from 'react';
import Button from '@material-ui/core/Button';
import Tooltip from '@material-ui/core/Tooltip';

// icons
import VerticalAlignTopIcon from '@material-ui/icons/VerticalAlignTop';
import VerticalAlignDownIcon from '@material-ui/icons/VerticalAlignBottom';
import VerticalAlignCenterIcon from '@material-ui/icons/VerticalAlignCenter';

function SetPinnedAction(props) {
    return (
        <Tooltip title="设置置顶">
            <Button
                variant="fab"
                mini={true}
                className={props.className}
                color="primary"
                onClick={() => props.onAction('setting', props.feed)}
            >
                <VerticalAlignTopIcon />
            </Button>
        </Tooltip>
    );
}

function UnsetPinnedAction(props) {
    return (
        <Tooltip title="取消置顶">
            <Button
                variant="fab"
                mini={true}
                className={props.className}
                color="primary"
                onClick={() => props.onAction('unsetting', props.feed)}
            >
                <VerticalAlignDownIcon />
            </Button>
        </Tooltip>
    );
}

function RenderPassAction(props) {
    return (
        <Tooltip title="审核置顶">
            <Button
                variant="fab"
                mini={true}
                color="primary"
                className={props.className}
                onClick={() => props.onAction('operation', props.feed)}
            >
                <VerticalAlignCenterIcon />
            </Button>
        </Tooltip>
    );
}

export default function RenderPinnedBottom (props) {
    let { onAction } = props;
    let { pinned } = props.feed;

    if (! pinned) {
        return (<SetPinnedAction {...props} />);
    }

    let { expires_at } = pinned;
    if (! expires_at) {
        return <RenderPassAction {...props} />;
    }

    let now = new Date();
    let expiresAt = new Date(expires_at);
    if (now.getTime() < expiresAt.getTime()) {
        return <UnsetPinnedAction {...props} />;
    }

    return (<SetPinnedAction {...props} />);
}