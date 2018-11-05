import green from '@material-ui/core/colors/green';
export default theme => ({
  headerBarButton: {
    marginRight: theme.spacing.unit,
    '&:last-child': {
      marginRight: 0
    }
  },
  fabProgress: {
    color: green[500],
    position: 'absolute',
    top: 0,
    left: 0,
    zIndex: 1,
  },
  wrapper: {
    position: 'relative',
    display: 'inline-block'
  },
  zoreRightMargin: {
    marginRight: 0
  },
});
