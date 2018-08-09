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
import Tooltip from '@material-ui/core/Tooltip';
import TableSortLabel from '@material-ui/core/TableSortLabel';
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
      handleDestroyTopic: PropTypes.func.isRequired,
      handleToggleTopicReview: PropTypes.func.isRequired
    }

    state = {
      add: false,
      edit: false,
      form: {
        name: '',
        desc: '',
      },
      hotTopic: 0,
      hotTopicAt: null,
      orderField: 'id',
      orderDirection: 'desc',
      review: null,
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

    handleOpenToggleTopicHot = (id, hotAt) => this.setState({ hotTopic: id, hotTopicAt: hotAt })

    handleCloseToggleTopicHot = () => this.setState({ hotTopic: 0, hotTopicAt: null })

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

    handleOrderCreator = (orderField, orderDirection) => () => {
      this.setState({ orderField, orderDirection });
      this.props.handleRequestTopics({ orderBy: orderField, direction: orderDirection, page: 1 });
    }

    handleOpenReview = (topic) => this.setState({
      review: topic
    })

    handleCloseReview = () => this.setState({ review: null })

    handleToggleReview = (id, status) => this.props.handleToggleTopicReview({
      id, status, successFn: () => this.handleCloseReview()
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
                  <TableCell
                    sortDirection={this.state.orderField === 'id' && this.state.orderDirection}
                  >
                    <Tooltip
                      title="按照 ID 排序（创建时间）"
                      enterDelay={300}
                    >
                      <TableSortLabel
                        active={this.state.orderField === 'id'}
                        direction={this.state.orderDirection}
                        onClick={this.handleOrderCreator('id', this.state.orderDirection === 'asc' ? 'desc' : 'asc')}
                      >
                        ID
                      </TableSortLabel>
                    </Tooltip>
                  </TableCell>
                  <TableCell>名称</TableCell>
                  <TableCell>审核状态&nbsp;/&nbsp;热门状态</TableCell>
                  
                  <TableCell
                    sortDirection={this.state.orderField === 'feeds_count' && this.state.orderDirection}
                  >
                    <Tooltip
                      title="按照动态数排序"
                      enterDelay={300}
                    >
                      <TableSortLabel
                        active={this.state.orderField === 'feeds_count'}
                        direction={this.state.orderDirection}
                        onClick={this.handleOrderCreator('feeds_count', this.state.orderDirection === 'asc' ? 'desc' : 'asc')}
                      >
                        动态数
                      </TableSortLabel>
                    </Tooltip>
                  </TableCell>

                  <TableCell
                    sortDirection={this.state.orderField === 'followers_count' && this.state.orderDirection}
                  >
                    <Tooltip
                      title="按照关注数排序"
                      enterDelay={300}
                    >
                      <TableSortLabel
                        active={this.state.orderField === 'followers_count'}
                        direction={this.state.orderDirection}
                        onClick={this.handleOrderCreator('followers_count', this.state.orderDirection === 'asc' ? 'desc' : 'asc')}
                      >
                        关注数
                      </TableSortLabel>
                    </Tooltip>
                  </TableCell>
                  
                  <TableCell>创建者</TableCell>
                  <TableCell>操作</TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {topics.map(topic => (
                  <TableRow key={topic.id}>
                    <TableCell component="th" scope="row">{ topic.id }</TableCell>
                    <TableCell>{ topic.name }</TableCell>
                    <TableCell>
                      {/* 审核状态 */}
                      <Tooltip title="点击切换审核状态">
                        <span
                          className={classes.statusButtons}
                          onClick={() => this.handleOpenReview(topic)}
                        >
                          {topic.status === 'waiting' && '待审核'}
                          {topic.status === 'failed' && '未通过'}
                          {topic.status === 'passed' && '通过'}
                        </span>
                      </Tooltip>
                      &nbsp;/&nbsp;
                      {/* 热门/取消按钮 */}
                      {topic.hot_at ? (
                        // 取消热门
                        <span
                          className={classes.statusButtons}
                          onClick={() => this.handleOpenToggleTopicHot(topic.id, topic.hot_at)}
                        >
                          取消热门
                        </span>
                      ) : (
                        // 热门
                        <span
                          className={classes.statusButtons}
                          onClick={() => this.handleOpenToggleTopicHot(topic.id, topic.hot_at)}
                        >
                          设为热门
                        </span>
                      )}
                    
                    </TableCell>
                    <TableCell>{ topic.feeds_count || 0 }</TableCell>
                    <TableCell>{ topic.followers_count || 0 }</TableCell>
                    <TableCell>{ topic.creator.name }</TableCell>
                    <TableCell classes={{ root: classes.tableActionCell }}>

                      {/* 编辑 */}
                      <Tooltip title="编辑话题">
                        <Button
                          variant="fab"
                          mini={true}
                          className={classes.actionsFab}
                          color="primary"
                          onClick={() => this.handleOpenEditForm(topic)}
                        >
                          <EditIcon />
                        </Button>
                      </Tooltip>

                      {/* 删除 */}
                      <Tooltip title="删除话题">
                        <Button
                          variant="fab"
                          mini={true}
                          className={classes.actionsFab}
                          color="secondary"
                          onClick={() => this.handleOpenDeleteTopic(topic.id)}
                        >
                          <DeleteIcon />
                        </Button>
                      </Tooltip>
                      
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
                {this.state.hotTopicAt ? '确认取消热门？' : '确认设置热门？'}
                <div className={classes.modalActions}>

                  {this.props.submitting && <CircularProgress size={36}/>}

                  <Button
                    color="primary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.handleToggleTopicHot}
                    disabled={this.props.submitting}
                  >
                    {this.state.hotTopicAt ? '取消热门' : '设置热门'}
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
          
          {this.renderReviewModal(this.state.review, classes)}

        </div>
      );
    }

    renderReviewModal(topic, classes) {
      if (!topic) {
        return null;
      }

      return (
        <Modal open={true}>
          <div className={classes.modalWrap} >
              <Paper
                classes={{
                  root: classes.modalPager
                }}
              >
                请选择你需要切换的话题状态：
                {this.props.submitting && <CircularProgress size={16}/>}
                <div className={classes.modalActions}>
                <Button
                  color="primary"
                  className={classes.actionsFab}
                  variant="contained"
                  disabled={this.props.submitting || topic.status === 'waiting'}
                  onClick={() => this.handleToggleReview(topic.id, 'waiting')}
                >
                  待审核
                </Button>
                <Button
                  color="primary"
                  className={classes.actionsFab}
                  variant="contained"
                  disabled={this.props.submitting || topic.status === 'passed'}
                  onClick={() => this.handleToggleReview(topic.id, 'passed')}
                >
                  通&nbsp;过
                </Button>
                <Button
                  color="primary"
                  className={classes.actionsFab}
                  variant="contained"
                  disabled={this.props.submitting || topic.status === 'failed'}
                  onClick={() => this.handleToggleReview(topic.id, 'failed')}
                >
                  未通过
                </Button>
                  <Button
                    color="secondary"
                    className={classes.actionsFab}
                    variant="contained"
                    onClick={this.handleCloseReview}
                    disabled={this.props.submitting}
                  >
                    关&nbsp;闭
                  </Button>
                </div>
              </Paper>
          </div>
        </Modal>
      );
    }
}

export default withStyles(styles)(ListView);
