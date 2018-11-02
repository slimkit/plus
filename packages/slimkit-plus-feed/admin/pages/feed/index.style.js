export default theme => ({
  root: {
    padding: theme.spacing.unit * 3
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
  reviewWrap: {
    width: 500,
    position: 'absolute',
    backgroundColor: theme.palette.background.paper,
    boxShadow: theme.shadows[5],
    top: '10%',
    left: '50%',
    marginLeft: -250,
    outline: 'none',
    maxHeight: '80%',
    overflowY: 'auto',
  },
  imageRoot: {
    display: 'flex',
    flexWrap: 'wrap',
    justifyContent: 'space-around',
    overflow: 'hidden',
    backgroundColor: theme.palette.background.paper,
  },
  imageGridList: {
    flexWrap: 'nowrap',
    transform: 'translateZ(0)',
  },
  previewVideo: {
    width: '100%',
    height: 'auto',
  },
  actions: {
    display: 'flex',
    // color: 'rgba(0, 0, 0, 0.26)',
  },
  tableActions: {
    minWidth: 200
  }
});
  