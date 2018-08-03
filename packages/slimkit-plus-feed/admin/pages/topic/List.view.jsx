import React from 'react';
import PropTypes from 'prop-types';
import withStyles from '@material-ui/core/styles/withStyles';
import Paper from '@material-ui/core/Paper';
import Divider from '@material-ui/core/Divider';
import Table from '@material-ui/core/Table';
import TableHead from '@material-ui/core/TableHead';
import TableBody from '@material-ui/core/TableBody';
import TableRow from '@material-ui/core/TableRow';
import TableCell from '@material-ui/core/TableCell';
import HeaderBar from './modules/ListContentHeaderBar';
import SearchBar from './modules/ListSearchBar';
import styles from './List.styles';

class ListView extends React.Component {
    static propTypes = {
      classes: PropTypes.object.isRequired,
      showSearchBar: PropTypes.bool.isRequired,
      handleSearchBarToggle: PropTypes.func.isRequired,
      handleRefresh: PropTypes.func.isRequired,
      handleRequestTopics: PropTypes.func.isRequired,
      loading: PropTypes.bool.isRequired,
      topics: PropTypes.array.isRequired
    }

    render() {
      let { classes, topics } = this.props;
      return (
        <div>
          <HeaderBar
            handleSearchBarToggle={this.props.handleSearchBarToggle}
            handleRefresh={this.props.handleRefresh}
            loading={this.props.loading}
          />
          <Paper className={classes.root}>
            <SearchBar
              show={this.props.showSearchBar}
              handleRequestTopics={this.props.handleRequestTopics}
            />
            {this.props.showSearchBar && <Divider />}
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
              <TableBody>
                {topics.map(topic => (
                  <TableRow key={topic.id}>
                    <TableCell component="th" scope="row">{ topic.id }</TableCell>
                    <TableCell>{ topic.name }</TableCell>
                    <TableCell>动态数</TableCell>
                    <TableCell>关注数</TableCell>
                    <TableCell>创建者</TableCell>
                    <TableCell>操作</TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          </Paper>
        </div>
      );
    }
}

export default withStyles(styles)(ListView);
