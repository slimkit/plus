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
import VerticalAlignTopIcon from '@material-ui/icons/VerticalAlignTop';
import VerticalAlignDownIcon from '@material-ui/icons/VerticalAlignBottom';
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
      submitting: PropTypes.bool.isRequired,
      handleSubmitAddForm: PropTypes.func.isRequired,
      message: PropTypes.object.isRequired,
      handleCloseMessage: PropTypes.func.isRequired,
      handleSubmitEditForm: PropTypes.func.isRequired,
      handleToggleTopicHot: PropTypes.func.isRequired,
      handleDestroyTopic: PropTypes.func.isRequired
    }

    state = {
      add: false,
      edit: false,
      form: {
        name: '',
        desc: '',
      },
      hotTopic: 0
    }

    handleOpenAddForm = () => {
      this.setState({
        add: true,
        form: {
          name: '',
          desc: ''
        }
      });
    }

    handleCloseForm = () => {
      this.setState({
        add: false,
        edit: false,
        form: {
          name: '',
          desc: ''
        }
      });
    }

    handleChangeFormInput = event => {
      this.setState({ form: {
        ...this.state.form,
        [event.target.name]: event.target.value
      } });
    }

    handleSubmitAddForm = () => this.props.handleSubmitAddForm(this.state.form, ({ submit, error, success }) => {
      submit().then(() => {
        this.handleCloseForm();
        success();
      }).catch(({ response: { data = { message: '添加失败' } } = {} }) => {
        error(data);
      });
    })

    handleOpenEditForm(topic) {
      this.setState({
        edit: topic.id,
        form: {
          name: topic.name,
          desc: topic.desc || ''
        }
      });
    }

    handleSubmitEditForm = () => {
      let { edit: id, form } = this.state;
      this.props.handleSubmitEditForm(id, form, ({ submit, success, error }) => submit().then(() => {
        this.handleCloseForm();
        success();
      }).catch(({ response: { data = { message: '编辑失败！' } } = {} }) => {
        error(data);
      }));
    };

    handleOpenToggleTopicHot = (id) => this.setState({ hotTopic: id })

    handleCloseToggleTopicHot = () => this.setState({ hotTopic: 0 })

    handleToggleTopicHot = () => this.props.handleToggleTopicHot(this.state.hotTopic, ({ submit, success, error }) => submit().then(() => {
      this.handleCloseToggleTopicHot();
      success();
    }).catch(({ response: { data = { message: '操作失败' } } = {} }) => error(data)))

    handleOpenDeleteTopic = (id) => this.setState({ deleteTopic: id })

    handleCloseDeleteTopic = () => this.setState({ deleteTopic: 0 })

    handleSubmitDeleteTopic = () => this.props.handleDestroyTopic(this.state.deleteTopic, ({ submit, success, error }) => submit().then(() => {
      this.handleCloseDeleteTopic();
      success();
    }).catch(({ response: { data = { message: '操作失败' } } = {} }) => error(data)))

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

                      {/* 热门/取消按钮 */}
                      {topic.hot_at ? (
                        // 取消热门
                        <Button
                          variant="fab"
                          mini={true}
                          className={classes.actionsFab}
                          onClick={() => this.handleOpenToggleTopicHot(topic.id)}
                        >
                          <VerticalAlignDownIcon />
                        </Button>
                      ) : (
                        // 热门
                        <Button
                          variant="fab"
                          mini={true}
                          className={classes.actionsFab}
                          onClick={() => this.handleOpenToggleTopicHot(topic.id)}
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
                        onClick={() => this.handleOpenEditForm(topic)}
                      >
                        <EditIcon />
                      </Button>

                      {/* 删除 */}
                      <Button
                        variant="fab"
                        mini={true}
                        className={classes.actionsFab}
                        color="secondary"
                        onClick={() => this.handleOpenDeleteTopic(topic.id)}
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
                        page: page + 1
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
            open={!!(this.state.add || this.state.edit)}
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
                  onChange={this.handleChangeFormInput}
                  value={this.state.form.name}
                  disabled={this.props.submitting}
                  required={true}
                />

                <TextField
                  fullWidth={true}
                  label="描述"
                  name="desc"
                  multiline={true}
                  rows={3}
                  rowsMax={5}
                  onChange={this.handleChangeFormInput}
                  value={this.state.form.desc}
                  disabled={this.props.submitting}
                />

                <div className={classes.modalActions}>

                  {this.props.submitting && <CircularProgress size={36}/>}

                  <Button
                    color="primary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.state.add === true ? this.handleSubmitAddForm : this.handleSubmitEditForm}
                    disabled={this.props.submitting}
                  >
                    { this.state.add === true ? '添 加' : '提 交' }
                  </Button>

                  <Button
                    color="secondary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.handleCloseForm}
                    disabled={this.props.submitting}
                  >
                    取&nbsp;消
                  </Button>
                </div>
              </Paper>
            </div>
          </Modal>

          {/* 热门状态切换 */}
          <Modal open={!!this.state.hotTopic}>
            <div className={classes.modalWrap} >
              <Paper
                classes={{
                  root: classes.modalPager
                }}
              >
                是否切换热门状态？
                <div className={classes.modalActions}>

                  {this.props.submitting && <CircularProgress size={36}/>}

                  <Button
                    color="primary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.handleToggleTopicHot}
                    disabled={this.props.submitting}
                  >
                    切&nbsp;换
                  </Button>

                  <Button
                    color="secondary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.handleCloseToggleTopicHot}
                    disabled={this.props.submitting}
                  >
                    取&nbsp;消
                  </Button>
                </div>
              </Paper>
            </div>
          </Modal>

          {/* 删除确认提示 */}
          <Modal open={!!this.state.deleteTopic}>
            <div className={classes.modalWrap} >
              <Paper
                classes={{
                  root: classes.modalPager
                }}
              >
                确认要删除话题嘛？
                <div className={classes.modalActions}>

                  {this.props.submitting && <CircularProgress size={36}/>}

                  <Button
                    color="primary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.handleCloseDeleteTopic}
                    disabled={this.props.submitting}
                  >
                    取&nbsp;消
                  </Button>

                  <Button
                    color="secondary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.handleSubmitDeleteTopic}
                    disabled={this.props.submitting}
                  >
                    删&nbsp;除
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
