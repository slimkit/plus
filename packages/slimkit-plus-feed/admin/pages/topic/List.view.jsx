import React from 'react';
import PropTypes from 'prop-types';
import withStyles from '@material-ui/core/styles/withStyles';
import Button from '@material-ui/core/Button';
import Paper from '@material-ui/core/Paper';
import Divider from '@material-ui/core/Divider';
import Table from '@material-ui/core/Table';
import TableHead from '@material-ui/core/TableHead';
import TableBody from '@material-ui/core/TableBody';
import TableRow from '@material-ui/core/TableRow';
import TableCell from '@material-ui/core/TableCell';
import TableFooter from '@material-ui/core/TableFooter';
import TablePagination from '@material-ui/core/TablePagination';
import Modal from '@material-ui/core/Modal';
import TextField from '@material-ui/core/TextField';
import CircularProgress from '@material-ui/core/CircularProgress';
import HeaderBar from './modules/ListContentHeaderBar';
import SearchBar from './modules/ListSearchBar';
import Snackbar from '../../components/common/Snackbar';
import styles from './List.styles';

// Icons
import VerticalAlignTopIcon from "@material-ui/icons/VerticalAlignTop";
import VerticalAlignDownIcon from "@material-ui/icons/VerticalAlignBottom";
import EditIcon from '@material-ui/icons/Edit';
import DeleteIcon from '@material-ui/icons/Delete';

class ListView extends React.Component {
    static propTypes = {
      classes: PropTypes.object.isRequired,
      showSearchBar: PropTypes.bool.isRequired,
      handleSearchBarToggle: PropTypes.func.isRequired,
      handleRefresh: PropTypes.func.isRequired,
      handleRequestTopics: PropTypes.func.isRequired,
      handleChangeLimit: PropTypes.func.isRequired,
      loading: PropTypes.bool.isRequired,
      topics: PropTypes.array.isRequired,
      total: PropTypes.number.isRequired,
      page: PropTypes.number.isRequired,
      limit: PropTypes.number.isRequired,
      addSubmitting: PropTypes.bool.isRequired,
      handleSubmitAddForm: PropTypes.func.isRequired,
      message: PropTypes.object.isRequired,
      handleCloseMessage: PropTypes.func.isRequired,
    }

    state = {
      add: false,
      addForm: {
        name: '',
        desc: '',
      }
    }

    handleOpenAddForm = () => {
      this.setState({ add: true });
    }

    handleCloseAddForm = () => {
      this.setState({
        add: false,
        addForm: {
          name: '',
          desc: ''
        }
      })
    }

    handleChangeAddFormInput = event => {
      this.setState({ addForm: {
        ...this.state.addForm,
        [event.target.name]: event.target.value
      } });
    }

    handleSubmitAddForm = () => {
      this.props.handleSubmitAddForm(this.state.addForm);
    }

    handleSubmitAddForm = () => this.props.handleSubmitAddForm(this.state.addForm, ({ submit, error, success }) => {
      submit().then(() => {
        this.handleCloseAddForm();
        success();
      }).catch(({ response: { data = { message: "添加失败" } } }) => {
        error(data);
      });
    })

    render() {
      let { classes, topics } = this.props;
      return (
        <div>
          <HeaderBar
            handleSearchBarToggle={this.props.handleSearchBarToggle}
            handleRefresh={this.props.handleRefresh}
            handleOpenAddForm={this.handleOpenAddForm}
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
                    <TableCell>{ topic.feeds_count || 0 }</TableCell>
                    <TableCell>{ topic.followers_count || 0 }</TableCell>
                    <TableCell>{ topic.creator.name }</TableCell>
                    <TableCell>

                      {/* 置顶/取消按钮 */}
                      {topic.hot_at ? (
                        // 取消置顶
                        <Button
                          variant="fab"
                          mini={true}
                          className={classes.actionsFab}
                        >
                          <VerticalAlignDownIcon />
                        </Button>
                      ) : (
                        // 置顶
                        <Button
                          variant="fab"
                          mini={true}
                          className={classes.actionsFab}
                        >
                          <VerticalAlignTopIcon />
                        </Button>
                      )}
                      

                      {/* 编辑 */}
                      <Button
                        variant="fab"
                        mini={true}
                        className={classes.actionsFab}
                        color="primary"
                      >
                        <EditIcon />
                      </Button>

                      {/* 删除 */}
                      <Button
                        variant="fab"
                        mini={true}
                        className={classes.actionsFab}
                        color="secondary"
                      >
                        <DeleteIcon />
                      </Button>
                      
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
              <TableFooter>
                <TableRow>
                  <TablePagination
                    count={this.props.total}
                    page={this.props.page - 1}
                    rowsPerPage={this.props.limit}
                    labelRowsPerPage="每页条数："
                    labelDisplayedRows={({from, to, count}) => `${from} 至 ${to}，总：${count}`}
                    onChangePage={(event, page) => {
                      this.props.handleRequestTopics({
                        page: page
                      });
                    }}
                    onChangeRowsPerPage={this.props.handleChangeLimit}
                  />
                </TableRow>
              </TableFooter>
            </Table>
          </Paper>

          {/* 消息提示 */}
          <Snackbar
            open={this.props.message.open}
            message={this.props.message.text}
            type={this.props.message.type}
            onClose={this.props.handleCloseMessage}
            autoHideDuration={3000}
          />

          {/* 添加话题 */}
          <Modal
            open={this.state.add}
          >
            <div
              className={classes.modalWrap}
            >
              <Paper
                classes={{
                  root: classes.modalPager
                }}
              >
                <TextField
                  fullWidth={true}
                  label="名称"
                  name="name"
                  type="text"
                  helperText="&nbsp;"
                  onChange={this.handleChangeAddFormInput}
                  value={this.state.addForm.name}
                  disabled={this.props.addSubmitting}
                />

                <TextField
                  fullWidth={true}
                  label="描述"
                  name="desc"
                  multiline={true}
                  rows={3}
                  rowsMax={5}
                  onChange={this.handleChangeAddFormInput}
                  value={this.state.addForm.desc}
                  disabled={this.props.addSubmitting}
                />

                <div className={classes.modalActions}>

                  {this.props.addSubmitting && <CircularProgress size={36}/>}

                  <Button
                    color="primary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.handleSubmitAddForm}
                    disabled={this.props.addSubmitting}
                  >
                    添&nbsp;加
                  </Button>

                  <Button
                    color="secondary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.handleCloseAddForm}
                    disabled={this.props.addSubmitting}
                  >
                    取&nbsp;消
                  </Button>
                </div>
              </Paper>
            </div>
          </Modal>
        </div>
      );
    }
}

export default withStyles(styles)(ListView);
