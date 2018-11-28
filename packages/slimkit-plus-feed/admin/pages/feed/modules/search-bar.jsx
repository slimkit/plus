import React from 'react';
import classnames from 'classnames';
import PropTypes from 'prop-types';
import Toolbar from '@material-ui/core/Toolbar';
import TextField from '@material-ui/core/TextField';
import FormControl from '@material-ui/core/FormControl';
import Select from '@material-ui/core/Select';
import InputLabel from '@material-ui/core/InputLabel';
import MenuItem from '@material-ui/core/MenuItem';
import Button from '@material-ui/core/Button';
import Autocomplete from '../../../components/common/autocomplete';

// styles
import { withStyles } from '@material-ui/core/styles';
import styles from './search-bar.style';

// APIs
import { search } from '../../../api/user';

// utils
import lodash from 'lodash';

class SearchBar extends React.Component
{
    static propTypes = {
      classes: PropTypes.object.isRequired,
      onSearch: PropTypes.func.isRequired,
      loading: PropTypes.bool.isRequired,
    }

    state = {
      user: null,
      id: '',
      keyword: '',
      pay: 'all',
      trashed: 0,
      top: 'all',
    };

    changeSearchState(event)
    {
      this.setState({
        [event.target.name]: event.target.value
      });
    }

    getUserValue()
    {
      let user = this.state.user;

      if (user instanceof Object) {
        return user.value;
      }

      return null;
    }

    getSearchParams()
    {
      return {
        user: this.getUserValue(),
        id: this.state.id,
        keyword: this.state.keyword,
        recommended: this.state.recommended,
        trashed: this.state.trashed,
        pay: this.state.pay,
        top: this.state.top,
      };
    }

    onSearch()
    {
      this.props.onSearch(
          this.getSearchParams()
      );
    }

    render()
    {
      let { classes, loading } = this.props;
      return (
        <Toolbar className={classes.root}>
          {/* 动态 ID */}
          <TextField
            name="id"
            type="number"
            min="1"
            label="ID"
            placeholder="请输入动态 ID"
            className={classes.formControl}
            value={this.state.id}
            onChange={this.changeSearchState.bind(this)}
            disabled={loading}
          />

          {/* 动态关键词 */}
          <TextField
            className={classes.formControl}
            name="keyword"
            type="string"
            label="关键词"
            placeholder="输入搜索关键词"
            value={this.state.keyword}
            onChange={this.changeSearchState.bind(this)}
            disabled={loading}
          />

          {/* 用户选择 */}
          <Autocomplete
            className={classnames(classes.searchUserWrap, classes.formControl)}
            optionsLoader={this.searchUserLoader.bind(this)}
            value={this.state.user}
            onChange={this.selectedSearchUserOnChangeHandler.bind(this)}
            placeholder="输入用户名搜索"
            fieldProps={{
              label: '用户',
              InputLabelProps: {
                shrink: true,
              },
            }}
            isDisabled={loading}
          />

          {/* pay */}
          <FormControl className={classes.formControl}>
            <InputLabel>付费</InputLabel>
            <Select
              name="pay"
              value={this.state.pay}
              onChange={this.changeSearchState.bind(this)}
              disabled={loading}
            >
              <MenuItem value={'all'}>全部</MenuItem>
              <MenuItem value={'free'}>仅免费</MenuItem>
              <MenuItem value={'paid'}>仅付费</MenuItem>
            </Select>
          </FormControl>

          {/* 置顶 */}
          <FormControl className={classes.formControl}>
            <InputLabel>置顶</InputLabel>
            <Select
              name="top"
              value={this.state.top}
              onChange={this.changeSearchState.bind(this)}
              disabled={loading}
            >
              <MenuItem value={'all'}>全部</MenuItem>
              <MenuItem value={'no'}>仅非置顶</MenuItem>
              <MenuItem value={'yes'}>仅已置顶</MenuItem>
              <MenuItem value={'wait'}>仅待审核</MenuItem>
              {/* <MenuItem value={'reject'}>拒绝/过期</MenuItem> */}
            </Select>
          </FormControl>

          {/* 是否存在回收站 */}
          <FormControl className={classes.formControl}>
            <InputLabel>状态</InputLabel>
            <Select
              name="trashed"
              value={this.state.trashed}
              onChange={this.changeSearchState.bind(this)}
              disabled={loading}
            >
              <MenuItem value={0}>仅可看的</MenuItem>
              <MenuItem value={1}>仅回收站</MenuItem>
            </Select>
          </FormControl>

          {/* 搜索按钮 */}
          <Button
            className={classes.baseMargin}
            variant="contained"
            color="primary"
            onClick={this.onSearch.bind(this)}
            disabled={loading}
          >
            搜&nbsp;索
          </Button>
        </Toolbar>
      );
    }

    selectedSearchUserOnChangeHandler(user) {
      this.setState({ user });
    }

    async searchUserLoader(keyword, next)
    {
      let { data: { users } } = await search(keyword);
      next(lodash.map(users, function (user) {
        return {
          value: user.id,
          label: user.name,
        };
      }));
    }
}

export default withStyles(styles)(SearchBar);
