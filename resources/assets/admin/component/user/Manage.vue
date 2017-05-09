<style lang="css" module>
.container {
  padding-top: 15px;
}
.loadding {
  text-align: center;
  font-size: 42px;
}
.loaddingIcon {
  animation-name: "TurnAround";
  animation-duration: 1.4s;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
}
</style>

<template>
  <div class="container-fluid" :class="$style.container">
    <div class="well well-sm">
      检索用户
      <router-link tag="a" class="btn btn-link pull-right btn-xs" to="/users/add" role="button">
        <span class="glyphicon glyphicon-plus"></span>
        添加用户
      </router-link>
    </div>

    <!-- 搜索用户 -->
    <div class="form-horizontal">
      <div class="form-group">
        <label for="search-input-id" class="col-sm-2 control-label">用户ID</label>
        <div class="col-sm-8">
          <div class="input-group">
            <div class="input-group-btn">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                排序 <span class="glyphicon" :class="(sort === 'up' ? 'glyphicon-triangle-top' : 'glyphicon-triangle-bottom')"></span>
              </button>
              <ul class="dropdown-menu">
                <li><a href="#" @click.prevent="changeUserIdSort('up')">
                  <span class="glyphicon glyphicon-triangle-top"></span>
                  由小到大
                </a></li>
                <li><a href="#" @click.prevent="changeUserIdSort('down')">
                  <span class="glyphicon glyphicon-triangle-bottom"></span>
                  由大到小
                </a></li>
              </ul>
            </div>
            <input v-model="userId" type="number" class="form-control" id="search-input-id" placeholder="按照用户ID搜索">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="search-input-email" class="col-sm-2 control-label">邮箱</label>
        <div class="col-sm-8">
          <input v-model="email" type="text" class="form-control" id="search-input-email" placeholder="请输入搜索邮箱地址，支持模糊搜索">
        </div>
      </div>
      <div class="form-group">
        <label for="search-input-phone" class="col-sm-2 control-label">手机号码</label>
        <div class="col-sm-8">
          <input v-model="phone" type="tel" class="form-control" id="search-input-phone" placeholder="请输入搜索手机号码，支持模糊搜索">
        </div>
      </div>
      <div class="form-group">
        <label for="search-input-name" class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-8">
          <input v-model="name" type="text" class="form-control" id="search-input-name" placeholder="请输入搜索用户名，支持模糊搜索">
        </div>
      </div>
      <div class="form-group">
        <label for="search-input-name" class="col-sm-2 control-label">角色</label>
        <div class="col-sm-8">
          <select v-model="role" class="form-control" id="search-input-name">
            <option value="0">全部</option>
            <option v-for="({ id, display_name }) in roles" :key="id" :value="id">{{ display_name }}</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <router-link class="btn btn-default" tag="button" :to="{ path: '/users', query: searchQuery }">
            搜索
          </router-link>
        </div>
      </div>
    </div>

    <!-- error -->
    <div v-show="error" class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" @click.prevent="dismisError">
        <span aria-hidden="true">&times;</span>
      </button>
      {{ error }}
    </div>

    <!-- 用户列表 -->
    <table class="table table-striped">
      <thead>
        <tr>
          <th>用户ID</th>
          <th>用户名</th>
          <th>邮箱</th>
          <th>手机号码</th>
          <th>注册时间</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-show="loadding">
          <!-- 加载动画 -->
          <td :class="$style.loadding" colspan="6">
            <span class="glyphicon glyphicon-refresh" :class="$style.loaddingIcon"></span>
          </td>
        </tr>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.phone }}</td>
          <td>{{ user.created_at }}</td>
          <td>
            <!-- 编辑 -->
            <router-link type="button" class="btn btn-primary btn-sm" :to="`/users/manage/${user.id}`" >编辑</router-link>
            <!-- 删除 -->
            <button v-if="deleteIds.indexOf(user.id) !== -1" type="button" class="btn btn-danger btn-sm" disabled="disabled">
              <span class="glyphicon glyphicon-refresh component-loadding-icon"></span>
            </button>
            <button v-else type="button" class="btn btn-danger btn-sm" @click="deleteUser(user.id)">删除</button>
          </td>
        </tr>
      </tbody>
    </table>
    <ul class="pager" v-show="page >= 1 && lastPage > 1">
      <li class="previous" :class="page <= 1 ? 'disabled' : ''">
        <router-link :to="{ path: '/users', query: prevQuery }">
          <span aria-hidden="true">&larr;</span>
          上一页
        </router-link>
      </li>
      <li class="next" :class="page >= lastPage ? 'disabled': ''">
        <router-link :to="{ path: '/users', query: nextQuery }">
          下一页
          <span aria-hidden="true">&rarr;</span>
        </router-link>
      </li>
    </ul>
  </div>
</template>

<script>
import request, { createRequestURI } from '../../util/request';
import lodash from 'lodash';

const ManageComponent = {
  /**
   * 定义当前组件状态数据
   *
   * @return {Object}
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  data: () => ({
    userId: '',
    sort: 'down',
    email: '',
    name: '',
    role: 0,
    phone: '',
    lastPage: 1,
    page: 1,
    perPage: 20,
    total: 0,
    users: [],
    loadding: false,
    showRole: true,
    roles: [],
    deleteIds: [],
    error: null
  }),
  computed: {
    queryParams () {
      const { userId, sort, email, name, phone, role, perPage, page } = this;
      return { userId, sort, email, name, phone, role, perPage, page };
    },
    prevQuery () {
      const page = parseInt(this.page);
      return {
        ...this.queryParams,
        lastPage: this.lastPage,
        page: page > 1 ? page - 1 : page
      };
    },
    nextQuery () {
      const page = parseInt(this.page);
      const lastPage = parseInt(this.lastPage);
      return {
        ...this.queryParams,
        lastPage: lastPage,
        page: page < lastPage ? page + 1 : lastPage
      };
    },
    searchQuery () {
      return {
        ...this.queryParams,
        page: 1
      };
    }
  },
  watch: {
    '$route' (to) {
      const {
        email = '',
        name = '',
        phone = '',
        role = 0,
        sort = 'down',
        userId = '',
        lastPage = 1,
        perPage = 20,
        page = 1
      } = to.query;

      this.email = email;
      this.name = name;
      this.phone = phone;
      this.role = role;
      this.sort = sort;
      this.userId = userId;
      this.lastPage = parseInt(lastPage);
      this.perPage = parseInt(perPage);
      this.page = parseInt(page);

      this.getUsers();
    }
  },
  /**
   * 定义方法组.
   *
   * @type {Object}
   */
  methods: {
    deleteIdsUnTo (userId) {
      let deleteIds = [];
      this.deleteIds.forEach(id => {
        if (parseInt(id) !== parseInt(userId)) {
          deleteIds.push(id);
        }
      });
      this.deleteIds = deleteIds;
    },
    deleteUser (userId) {
      if (window.confirm('确定要删除用户吗？')) {
        this.deleteIds = [ ...this.deleteIds, userId ];
        request.delete(
          createRequestURI(`users/${userId}`),
          { validateStatus: status => status === 204 }
        ).then(() => {
          this.deleteIdsUnTo(userId);
          let users = [];
          this.users.forEach(user => {
            if (user.id !== userId) {
              users.push(user);
            }
          });
          this.users = users;
        }).catch(({ response: { data: { errors = ['删除失败'] } = {} } = {} }) => {
          this.deleteIdsUnTo(userId);
          this.error = lodash.values(errors).pop();
        });
      }
    },
    /**
     * 改变用户排序状态方法.
     *
     * @enum {up, down}
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    changeUserIdSort (sort) {
      this.sort = sort;
    },
    /**
     * 获取列表用户.
     *
     * @author Seven Du <shiweidu@outlook.com>
     * @homepage http://medz.cn
     */
    getUsers () {
      this.loadding = true;
      request.get(
        createRequestURI('users'),
        {
          params: { ...this.queryParams, show_role: this.showRole },
          validateStatus: status => status === 200
        }
      ).then(response => {
        const { page: data, roles } = response.data;

        this.users = data.data || [];
        this.lastPage = parseInt(data.last_page);
        this.total = parseInt(data.total);
        this.loadding = false;
        this.showRole = false;

        this.roles = roles;
      }).catch(({ response: { data: { errors = ['加载用户失败'] } = {} } = {} }) => {
        this.error = lodash.values(errors).pop();
        this.loadding = false;
      });
    },
    dismisError () {
      this.error = null;
    }
  },
  /**
   * 组件创建完成后.
   *
   * @author Seven Du <shiweidu@outlook.com>
   * @homepage http://medz.cn
   */
  created () {
    const {
      email = '',
      name = '',
      phone = '',
      role = 0,
      sort = 'down',
      userId = '',
      lastPage = 1,
      perPage = 20,
      page = 1
    } = this.$route.query;
    // set state.
    this.email = email;
    this.name = name;
    this.phone = phone;
    this.role = role;
    this.sort = sort;
    this.userId = userId;
    this.lastPage = parseInt(lastPage);
    this.perPage = parseInt(perPage);
    this.page = parseInt(page);
    this.showRole = true;

    this.getUsers();
  }
};

export default ManageComponent;
</script>
