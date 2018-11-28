import green from '@material-ui/core/colors/green';
export default theme => ({
    root: {
        width: '100%',
        height: '100%',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
    },
    box: {
        width: theme.spacing.unit * 50,
    },
    headerBarTitle: {
        flexGrow: 1,
    },
    wrap: {
        width: '100%',
        paddingTop: theme.spacing.unit * 3,
        paddingRight: theme.spacing.unit * 3,
        paddingLeft: theme.spacing.unit * 3,
        paddingBottom: theme.spacing.unit,
    },
    modalActions: {
        width: '100%',
        paddingTop: theme.spacing.unit * 2,
        display: 'flex',
        justifyContent: 'flex-end',
        alignItems: 'center',
    },
    actionsFab: {
        margin: theme.spacing.unit,
        '&:first-child': {
            marginLeft: 0
        },
        '&:last-child': {
            marginRight: 0
        }
    },
    actionsWrapper: {
        position: 'relative',
    },
    buttonProgress: {
        color: green[500],
        position: 'absolute',
        top: '50%',
        left: '50%',
        marginTop: -12,
        marginLeft: -12,
    },
});
