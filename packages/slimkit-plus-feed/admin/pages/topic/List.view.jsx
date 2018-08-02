import React from 'react';
import PropTypes from 'prop-types';
import withStyles from '@material-ui/core/styles/withStyles';
import Paper from '@material-ui/core/Paper';
import Toolbar from '@material-ui/core/Toolbar';
import TextField from '@material-ui/core/TextField';
import Button from '@material-ui/core/Button';
import Divider from '@material-ui/core/Divider';
import InputLabel from '@material-ui/core/InputLabel';
import MenuItem from '@material-ui/core/MenuItem';
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';
import Table from '@material-ui/core/Table';
import TableHead from '@material-ui/core/TableHead';
import TableRow from '@material-ui/core/TableRow';
import TableCell from '@material-ui/core/TableCell';
import HeaderBar from './modules/ListContentHeaderBar';
import styles from './List.styles';

class ListView extends React.Component {
    static propTypes = {
      classes: PropTypes.object.isRequired
    }

    state = {
      name: '',
      hot: 0
    }

    handleChangeInput = event => {
      this.setState({ [event.target.name]: event.target.value });
    }

    handleResetSearch = () => {
      this.setState({
        name: '',
        hot: 0
      });
    }

    render() {
      let { classes } = this.props;
      return (
        <div>
          <HeaderBar />
          <Paper className={classes.root}>
            <Toolbar className={classes.toolbar}>
              <TextField
                name="name"
                value={this.state.name}
                className={classes.formControl}
                label="名称"
                placeholder="输入名称关键词..."
                onChange={this.handleChangeInput}
              />
              <FormControl className={classes.formControl}>
                <InputLabel>热门</InputLabel>
                <Select
                  value={this.state.hot}
                  onChange={this.handleChangeInput}
                  name="hot"
                >
                  <MenuItem value={0}>否</MenuItem>
                  <MenuItem value={1}>是</MenuItem>
                </Select>
              </FormControl>
              <Button className={classes.baseMargin} variant="contained" color="primary">搜&nbsp;索</Button>
              <Button className={classes.baseMargin} variant="contained" onClick={this.handleResetSearch}>重&nbsp;置</Button>
            </Toolbar>
            <Divider />
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell>ID</TableCell>
                  <TableCell>名称</TableCell>
                  <TableCell>动态数</TableCell>
                  <TableCell>关注数</TableCell>
                  <TableCell>创建者</TableCell>
                  <TableCell>操作</TableCell>
                </TableRow>
              </TableHead>
            </Table>
          </Paper>
        </div>
      );
    }
}

export default withStyles(styles)(ListView);
