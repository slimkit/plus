export default theme => ({
  root: {
    width: '100%',
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
  modalPager: {
    width: theme.spacing.unit * 50,
    paddingTop: theme.spacing.unit * 3,
    paddingRight: theme.spacing.unit * 3,
    paddingLeft: theme.spacing.unit * 3,
    paddingBottom: theme.spacing.unit,
  },
  modalWrap: {
    width: '100%',
    height: '100%',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
  },
  modalActions: {
    width: '100%',
    paddingTop: theme.spacing.unit * 2,
    display: 'flex',
    justifyContent: 'flex-end',
    alignItems: 'center',
  },
  statusButtons: {
    margin: 0,
    padding: 0,
    cursor: 'pointer',
    color: theme.palette.primary.main
  },
  tableActionCell: {
    minWidth: 150
  }
});