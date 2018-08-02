export default theme => ({
  root: {
    width: '100%',
  },
  baseMargin: {
    margin: theme.spacing.unit,
  },
  toolbar: {
    paddingTop: theme.spacing.unit * 3,
    marginBottom: theme.spacing.unit * 2
  },
  formControl: {
    margin: `0 ${theme.spacing.unit}px`,
    minWidth: 120,
    '&:first-child': {
      marginLeft: 0
    }
  }
});