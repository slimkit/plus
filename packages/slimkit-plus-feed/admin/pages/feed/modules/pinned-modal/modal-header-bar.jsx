import React from 'react';
import PropTypes from 'prop-types';
import Typography from '@material-ui/core/Typography';
import AppBar from '@material-ui/core/AppBar';
import Toolbar from '@material-ui/core/Toolbar';
import IconButton from '@material-ui/core/IconButton';
import CloseIcon from '@material-ui/icons/Close';

class HeaderBar extends React.Component {

    static propTypes = {
        classes: PropTypes.object.isRequired,
        title: PropTypes.string,
        onClose: PropTypes.func.isRequired,
        disabled: PropTypes.bool,
    };

    render() {

        let { classes, title, onClose, disabled } = this.props;

        return (
            <AppBar position="static">
                <Toolbar>
                    <Typography variant="h6" color="inherit" className={classes.headerBarTitle}>
                        {title}
                    </Typography>
                    {!disabled && (
                        <IconButton color="inherit" onClick={onClose}>
                            <CloseIcon />
                        </IconButton>
                    )}
                </Toolbar>
            </AppBar>
        );
    }
}

export default HeaderBar;
